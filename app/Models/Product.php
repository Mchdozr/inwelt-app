<?php

namespace App\Models;

use App\Support\Money;
use App\Support\ProductMarketplace;
use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'gtin13',
        'badge',
        'tags',
        'summary',
        'description',
        'cover_image',
        'og_image',
        'pdf_path',
        'seller_url',
        'price',
        'compare_at_price',
        'currency',
        'price_synced_at',
        'trendyol_url',
        'hepsiburada_url',
        'trendyol_price',
        'hepsiburada_price',
        'prices_synced_at',
        'prices_locked',
        'is_featured',
        'is_advantageous',
        'is_active',
        'sort',
        'seo_title',
        'seo_description',
        'rating_value',
        'rating_count',
        'faq_items',
        'related_guide_slugs',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_advantageous' => 'boolean',
        'is_active' => 'boolean',
        'prices_locked' => 'boolean',
        'tags' => 'array',
        'faq_items' => 'array',
        'related_guide_slugs' => 'array',
        'sort' => 'integer',
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'price_synced_at' => 'datetime',
        'trendyol_price' => 'decimal:2',
        'hepsiburada_price' => 'decimal:2',
        'prices_synced_at' => 'datetime',
        'rating_value' => 'decimal:1',
        'rating_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort');
    }

    public function useCases(): HasMany
    {
        return $this->hasMany(UseCase::class)->orderBy('sort');
    }

    public function resolvedSku(): string
    {
        return $this->sku ?: 'INWELT-'.Str::upper(Str::limit($this->slug, 40, ''));
    }

    public function rawMarketplacePrice(string $marketplace): float|string|null
    {
        return match ($marketplace) {
            'kacmasa' => $this->price,
            'trendyol' => $this->trendyol_price,
            'hepsiburada' => $this->hepsiburada_price,
            default => null,
        };
    }

    public function marketplacePrice(string $marketplace): ?string
    {
        return Money::formatTry($this->rawMarketplacePrice($marketplace));
    }

    public function marketplacePriceLabel(string $marketplace): ?string
    {
        $formatted = $this->marketplacePrice($marketplace);

        if ($formatted !== null) {
            return $formatted;
        }

        if (! $this->canSyncMarketplacePrice($marketplace)) {
            return null;
        }

        return 'Fiyat güncelleniyor';
    }

    public function canSyncMarketplacePrice(string $marketplace): bool
    {
        return match ($marketplace) {
            'kacmasa' => ProductMarketplace::kacmasaUrl($this) !== null,
            'trendyol', 'hepsiburada' => ProductMarketplace::hasProductPageUrl($this, $marketplace),
            default => false,
        };
    }

    public function hasPriceDropBadge(): bool
    {
        $tags = $this->tags ?? [];

        return $this->is_advantageous
            || in_array('deal', $tags, true)
            || in_array('flash', $tags, true);
    }

    public function marketplacePrices(): Collection
    {
        return collect([$this->price, $this->trendyol_price, $this->hepsiburada_price])
            ->filter(fn ($price) => $price !== null && (float) $price > 0)
            ->map(fn ($price) => (float) $price)
            ->values();
    }

    public function lowestRawPrice(): ?float
    {
        $prices = $this->marketplacePrices();

        return $prices->isEmpty() ? null : (float) $prices->min();
    }

    public function hasFreshMarketplacePrices(): bool
    {
        if ($this->marketplacePrices()->isEmpty()) {
            return false;
        }

        if ($this->prices_synced_at && $this->prices_synced_at->lt(now()->subDays(7))) {
            return false;
        }

        return true;
    }

    /**
     * @return array{low: string, high: string}|null
     */
    public function displayPriceRange(): ?array
    {
        if (! $this->hasFreshMarketplacePrices()) {
            return null;
        }

        $prices = $this->marketplacePrices();

        return [
            'low' => Money::formatTry($prices->min()) ?? '',
            'high' => Money::formatTry($prices->max()) ?? '',
        ];
    }

    /**
     * @return Collection<int, Guide>
     */
    public function relatedGuides(): Collection
    {
        $slugs = array_values(array_filter($this->related_guide_slugs ?? []));

        if ($slugs === []) {
            return Guide::query()
                ->published()
                ->when($this->category_id, fn ($query) => $query->where('category_id', $this->category_id))
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();
        }

        return Guide::query()
            ->published()
            ->whereIn('slug', $slugs)
            ->get();
    }

    /**
     * @return Collection<int, Product>
     */
    public function relatedProducts(int $limit = 4): Collection
    {
        $tags = $this->tags ?? [];

        return static::query()
            ->with('category')
            ->where('is_active', true)
            ->where('id', '!=', $this->id)
            ->where('category_id', $this->category_id)
            ->get()
            ->sortByDesc(function (self $candidate) use ($tags) {
                $overlap = count(array_intersect($tags, $candidate->tags ?? []));
                $priceScore = 0;

                if ($this->price && $candidate->price) {
                    $delta = abs((float) $this->price - (float) $candidate->price);
                    $priceScore = max(0, 1000 - $delta);
                }

                return ($overlap * 1000) + $priceScore - $candidate->sort;
            })
            ->take($limit)
            ->values();
    }

    protected static function booted(): void
    {
        static::saving(function (self $product): void {
            if (! filled($product->sku) && filled($product->slug)) {
                $product->sku = 'INWELT-'.Str::upper(Str::limit($product->slug, 40, ''));
            }

            if ($product->isDirty(['price', 'trendyol_price', 'hepsiburada_price', 'compare_at_price'])
                && ! $product->isDirty('prices_synced_at')) {
                $product->prices_synced_at = now();
            }
        });

        static::saved(function (self $product): void {
            SiteCache::forgetAll();
            \App\Support\IndexNow::ping(route('products.show', $product->slug));
        });

        static::deleted(fn () => SiteCache::forgetAll());
    }
}

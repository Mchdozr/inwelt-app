<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'badge',
        'tags',
        'summary',
        'description',
        'cover_image',
        'pdf_path',
        'seller_url',
        'price',
        'compare_at_price',
        'currency',
        'price_synced_at',
        'trendyol_url',
        'hepsiburada_url',
        'is_featured',
        'is_advantageous',
        'is_active',
        'sort',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_advantageous' => 'boolean',
        'is_active' => 'boolean',
        'tags' => 'array',
        'sort' => 'integer',
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'price_synced_at' => 'datetime',
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

    public function hasPriceDropBadge(): bool
    {
        if ($this->compare_at_price !== null && $this->price !== null) {
            return (float) $this->compare_at_price > (float) $this->price;
        }

        return $this->is_advantageous;
    }

    public function formattedPrice(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        $symbol = match ($this->currency ?: 'TRY') {
            'TRY' => 'TL',
            default => $this->currency,
        };

        return number_format((float) $this->price, 2, ',', '.').' '.$symbol;
    }

    public function formattedCompareAtPrice(): ?string
    {
        if ($this->compare_at_price === null) {
            return null;
        }

        $symbol = match ($this->currency ?: 'TRY') {
            'TRY' => 'TL',
            default => $this->currency,
        };

        return number_format((float) $this->compare_at_price, 2, ',', '.').' '.$symbol;
    }

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forgetAll());
        static::deleted(fn () => SiteCache::forgetAll());
    }
}

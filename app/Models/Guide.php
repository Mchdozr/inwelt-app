<?php

namespace App\Models;

use App\Support\IndexNow;
use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Guide extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'category_id',
        'author_id',
        'cover_image',
        'og_image',
        'seo_title',
        'seo_description',
        'reading_time_minutes',
        'tags',
        'faq_items',
        'toc',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'faq_items' => 'array',
        'toc' => 'array',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'reading_time_minutes' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function relatedProducts(int $limit = 4)
    {
        $query = Product::query()->with('category')->where('is_active', true);

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        return $query->orderBy('sort')->limit($limit)->get();
    }

    public static function tocFromBody(string $body): array
    {
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/si', $body, $matches);

        return collect($matches[1] ?? [])
            ->map(function (string $heading) {
                $text = trim(html_entity_decode(strip_tags($heading)));

                return [
                    'id' => Str::slug($text),
                    'text' => $text,
                ];
            })
            ->filter(fn ($item) => $item['text'] !== '')
            ->values()
            ->all();
    }

    protected static function booted(): void
    {
        static::saving(function (self $guide): void {
            $plain = trim(preg_replace('/\s+/', ' ', strip_tags($guide->body)) ?? '');
            $guide->reading_time_minutes = max(1, (int) ceil(str_word_count($plain) / 200));
            $guide->toc = self::tocFromBody($guide->body);
        });

        static::saved(function (self $guide): void {
            SiteCache::forgetAll();

            if ($guide->is_active && $guide->published_at) {
                IndexNow::ping(route('guides.show', $guide->slug));
            }
        });

        static::deleted(fn () => SiteCache::forgetAll());
    }
}

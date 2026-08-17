<?php

namespace App\Models;

use App\Support\IndexNow;
use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'icon',
        'description',
        'landing_intro',
        'seo_title',
        'seo_description',
        'seo_content',
        'hero_image',
        'faq_items',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
        'faq_items' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort');
    }

    public function guides(): HasMany
    {
        return $this->hasMany(Guide::class);
    }

    protected static function booted(): void
    {
        static::saved(function (self $category): void {
            SiteCache::forgetAll();
            IndexNow::ping(route('products.category', $category->slug));
        });

        static::deleted(fn () => SiteCache::forgetAll());
    }
}

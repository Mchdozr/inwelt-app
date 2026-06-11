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

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forgetAll());
        static::deleted(fn () => SiteCache::forgetAll());
    }
}

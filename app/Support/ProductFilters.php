<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

class ProductFilters
{
    public const LABELS = [
        'flash' => 'Flaş Ürünler',
        'high-rated' => 'Yüksek Puanlı Ürünler',
        'free-shipping' => 'Kargo Bedava',
        'fast-delivery' => 'Hızlı Teslimat',
        'bestseller' => 'Çok Satanlar',
        'smart-devices' => 'Akıllı Cihazlar',
        'deal' => 'Fırsat Ürünleri',
        'new-arrival' => 'Yeni Gelenler',
        'gift' => 'Hediye Fikirleri',
    ];

    public static function isValid(string $slug): bool
    {
        return array_key_exists($slug, self::LABELS);
    }

    public static function araToSlug(?string $ara): ?string
    {
        if (! $ara) {
            return null;
        }

        $normalized = trim($ara);

        foreach (self::LABELS as $slug => $label) {
            if (strcasecmp($normalized, $label) === 0) {
                return $slug;
            }
        }

        if (strcasecmp($normalized, 'Fırsat') === 0) {
            return 'deal';
        }

        return null;
    }

    public static function apply(Builder $query, string $slug): void
    {
        match ($slug) {
            'smart-devices' => $query->whereHas(
                'category',
                fn (Builder $q) => $q->where('slug', 'akilli-cihazlar')
            ),
            default => $query->whereJsonContains('tags', $slug),
        };
    }
}

<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

class ProductFilters
{
    public const LABELS = [
        'flash' => 'Flash Ürünler',
        'high-rated' => 'Yüksek Puanlı Ürünler',
        'free-shipping' => 'Kargo Bedava',
        'fast-delivery' => 'Hızlı Teslimat',
        'bestseller' => 'Çok Satanlar',
        'smart-devices' => 'Akıllı Cihazlar',
        'deal' => 'Fırsat Ürünler',
        'new-arrival' => 'Yeni Gelenler',
        'gift' => 'Hediye Fikirleri',
    ];

    /** @var list<array{0: string, 1: string, 2: string}> */
    public const NAV_QUICK_FILTERS = [
        ['Tüm Ürünler', 'slate', ''],
        ['Flash Ürünler', 'orange', 'flash'],
        ['Fırsat Ürünler', 'orange', 'deal'],
        ['Çok Satanlar', 'yellow', 'bestseller'],
        ['Yeni Gelenler', 'blue', 'new-arrival'],
        ['Kargo Bedava', 'green', 'free-shipping'],
        ['Hızlı Teslimat', 'green', 'fast-delivery'],
        ['Hediye Fikirleri', 'pink', 'gift'],
        ['Akıllı Cihazlar', 'blue', 'smart-devices'],
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

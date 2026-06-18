<?php

namespace App\Support;

use App\Models\Product;

final class ProductMarketplace
{
    public static function kacmasaUrl(Product $product): ?string
    {
        if (! $product->seller_url) {
            return null;
        }

        return OutboundLink::withUtm($product->seller_url, 'kacmasa', $product->slug);
    }

    public static function kacmasaStoreUrl(?string $productSlug = null): string
    {
        return OutboundLink::withUtm('https://kacmasa.com/magaza/NWELT', 'kacmasa', $productSlug);
    }

    public static function trendyolUrl(Product $product): string
    {
        $base = $product->trendyol_url
            ?: 'https://www.trendyol.com/sr?q='.urlencode($product->name);

        return OutboundLink::withUtm($base, 'trendyol', $product->slug);
    }

    public static function hepsiburadaUrl(Product $product): string
    {
        $base = $product->hepsiburada_url
            ?: 'https://www.hepsiburada.com/ara?q='.urlencode($product->name);

        return OutboundLink::withUtm($base, 'hepsiburada', $product->slug);
    }

    public static function hasProductPageUrl(Product $product, string $marketplace): bool
    {
        $url = match ($marketplace) {
            'trendyol' => $product->trendyol_url,
            'hepsiburada' => $product->hepsiburada_url,
            default => null,
        };

        if (! is_string($url) || $url === '') {
            return false;
        }

        return match ($marketplace) {
            'trendyol' => ! str_contains($url, 'trendyol.com/sr'),
            'hepsiburada' => ! str_contains($url, 'hepsiburada.com/ara'),
            default => false,
        };
    }
}

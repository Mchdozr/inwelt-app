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
}

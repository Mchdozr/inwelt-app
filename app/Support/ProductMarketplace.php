<?php

namespace App\Support;

use App\Models\Product;

final class ProductMarketplace
{
    public static function trendyolUrl(Product $product): string
    {
        return 'https://www.trendyol.com/sr?q='.urlencode($product->name);
    }

    public static function hepsiburadaUrl(Product $product): string
    {
        return 'https://www.hepsiburada.com/ara?q='.urlencode($product->name);
    }
}

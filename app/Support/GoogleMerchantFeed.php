<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

final class GoogleMerchantFeed
{
    public static function xml(): string
    {
        $products = Product::query()
            ->with(['images', 'category'])
            ->where('is_active', true)
            ->orderBy('sort')
            ->get()
            ->filter(fn (Product $product) => $product->lowestRawPrice() !== null)
            ->values();

        return view('feeds.google-merchant', compact('products'))->render();
    }

    public static function imageUrl(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        return url(Storage::url($path));
    }
}

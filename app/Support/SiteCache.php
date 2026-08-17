<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class SiteCache
{
    public const TTL = 3600;

    public static function forgetAll(): void
    {
        Cache::forget('home_page');
        Cache::forget('nav_categories');
        Cache::forget('product_sidebar_categories');
        Cache::forget('sitemap_xml');
        Cache::forget('sitemap_index');
        Cache::forget('sitemap_pages');
        Cache::forget('sitemap_products');
        Cache::forget('sitemap_categories');
        Cache::forget('sitemap_guides');
        Cache::forget('published_guides');
    }
}

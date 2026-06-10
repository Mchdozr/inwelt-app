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
    }
}

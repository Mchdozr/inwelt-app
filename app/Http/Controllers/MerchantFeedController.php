<?php

namespace App\Http\Controllers;

use App\Support\GoogleMerchantFeed;
use App\Support\SiteCache;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class MerchantFeedController extends Controller
{
    public function google(): Response
    {
        $xml = Cache::remember('google_merchant_feed', SiteCache::TTL, fn () => GoogleMerchantFeed::xml());

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\HeroShowcase;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $data = Cache::remember('home_page', SiteCache::TTL, function () {
            return [
                'categories' => Category::with('products')
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort')
                    ->limit(6)
                    ->get(),
                'featured' => Product::with('category')
                    ->where('is_featured', true)
                    ->where('is_active', true)
                    ->orderBy('sort')
                    ->limit(15)
                    ->get(),
            ];
        });

        return view('pages.home', [
            ...$data,
            'heroVisual' => HeroShowcase::random(),
        ]);
    }
}

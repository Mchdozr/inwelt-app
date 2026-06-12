<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\SiteCache;
use Illuminate\Http\Response;
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

        return view('pages.home', $data);
    }

    public function sitemap(): Response
    {
        $xml = Cache::remember('sitemap_xml', SiteCache::TTL, function () {
            $categories = Category::where('is_active', true)->orderBy('sort')->get();
            $products = Product::where('is_active', true)->orderBy('updated_at', 'desc')->get();

            return view('sitemap', compact('categories', 'products'))->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /admin/',
            '',
            'Sitemap: ' . route('sitemap'),
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Product;
use App\Support\SiteCache;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap_index', SiteCache::TTL, fn () => view('sitemaps.index')->render());

        return $this->xml($xml);
    }

    public function pages(): Response
    {
        $xml = Cache::remember('sitemap_pages', SiteCache::TTL, fn () => view('sitemaps.pages')->render());

        return $this->xml($xml);
    }

    public function products(): Response
    {
        $xml = Cache::remember('sitemap_products', SiteCache::TTL, function () {
            $products = Product::where('is_active', true)->orderByDesc('updated_at')->get();

            return view('sitemaps.products', compact('products'))->render();
        });

        return $this->xml($xml);
    }

    public function categories(): Response
    {
        $xml = Cache::remember('sitemap_categories', SiteCache::TTL, function () {
            $categories = Category::where('is_active', true)->orderBy('sort')->get();

            return view('sitemaps.categories', compact('categories'))->render();
        });

        return $this->xml($xml);
    }

    public function guides(): Response
    {
        $xml = Cache::remember('sitemap_guides', SiteCache::TTL, function () {
            $guides = Guide::published()->orderByDesc('updated_at')->get();

            return view('sitemaps.guides', compact('guides'))->render();
        });

        return $this->xml($xml);
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /admin/',
            '',
            'User-agent: GPTBot',
            'Allow: /',
            'Disallow: /admin',
            '',
            'User-agent: ClaudeBot',
            'Allow: /',
            'Disallow: /admin',
            '',
            'User-agent: PerplexityBot',
            'Allow: /',
            'Disallow: /admin',
            '',
            'User-agent: Google-Extended',
            'Allow: /',
            'Disallow: /admin',
            '',
            'User-agent: OAI-SearchBot',
            'Allow: /',
            'Disallow: /admin',
            '',
            'Sitemap: '.route('sitemap'),
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function llms(): Response
    {
        $content = implode("\n", [
            '# INWELT',
            '',
            '> INWELT, akıllı cihaz, RC oyuncak, müzik, zeka oyunları ve kişisel bakım ürünlerinde marka vitrinidir.',
            '> Satın alma Kacmasa, Trendyol ve Hepsiburada mağazaları üzerinden tamamlanır.',
            '',
            'Site: https://inwelt.com.tr',
            '',
            '## Önemli sayfalar',
            '- Ana sayfa: '.url('/anasayfa'),
            '- Ürünler: '.route('products.index'),
            '- Rehberler: '.route('guides.index'),
            '- SSS: '.route('faq'),
            '- Hakkımızda: '.route('about'),
            '- İletişim: '.route('contact'),
            '',
            '## Kullanım',
            'İçerikler kaynak gösterilerek alıntılanabilir. Fiyatlar pazaryerlerinden senkronize edilir ve 7 gün geçerlidir.',
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function indexNowKey(string $key): Response
    {
        $expected = \App\Support\IndexNow::key();

        abort_unless($expected && hash_equals($expected, $key), 404);

        return response($expected, 200)->header('Content-Type', 'text/plain');
    }

    private function xml(string $xml): Response
    {
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}

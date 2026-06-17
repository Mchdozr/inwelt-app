<?php

namespace Tests\Unit;

use App\Services\KacmasaCatalogParser;
use App\Support\OutboundLink;
use App\Support\ProductMarketplace;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutboundLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_outbound_link_appends_utm_parameters(): void
    {
        $url = OutboundLink::withUtm('https://kacmasa.com/urun', 'kacmasa', 'test-urun');

        $this->assertStringContainsString('utm_source=inwelt', $url);
        $this->assertStringContainsString('utm_campaign=kacmasa', $url);
        $this->assertStringContainsString('utm_content=test-urun', $url);
    }

    public function test_marketplace_urls_use_product_specific_links_when_set(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'sort' => 0,
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Ürün',
            'slug' => 'test-urun',
            'seller_url' => 'https://kacmasa.com/test',
            'trendyol_url' => 'https://www.trendyol.com/urun/test',
            'hepsiburada_url' => 'https://www.hepsiburada.com/urun/test',
            'is_active' => true,
            'sort' => 0,
        ]);

        $this->assertStringStartsWith('https://kacmasa.com/test?', ProductMarketplace::kacmasaUrl($product));
        $this->assertStringStartsWith('https://www.trendyol.com/urun/test?', ProductMarketplace::trendyolUrl($product));
        $this->assertStringStartsWith('https://www.hepsiburada.com/urun/test?', ProductMarketplace::hepsiburadaUrl($product));
    }

    public function test_kacmasa_parser_extracts_prices_from_saved_html(): void
    {
        $html = file_get_contents(base_path('scripts/kacmasa-page-1.html'));
        $items = (new KacmasaCatalogParser)->parseListingHtml($html);

        $this->assertNotEmpty($items);
        $this->assertNotNull($items[0]['price']);
    }

    public function test_price_drop_badge_requires_compare_at_price(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-2',
            'sort' => 0,
            'is_active' => true,
        ]);

        $discounted = Product::create([
            'category_id' => $category->id,
            'name' => 'İndirimli',
            'slug' => 'indirimli',
            'price' => 100,
            'compare_at_price' => 150,
            'is_active' => true,
            'sort' => 0,
        ]);

        $tagOnly = Product::create([
            'category_id' => $category->id,
            'name' => 'Etiketli',
            'slug' => 'etiketli',
            'tags' => ['deal'],
            'is_advantageous' => false,
            'is_active' => true,
            'sort' => 1,
        ]);

        $this->assertTrue($discounted->hasPriceDropBadge());
        $this->assertFalse($tagOnly->hasPriceDropBadge());
    }
}

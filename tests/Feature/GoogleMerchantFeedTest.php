<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleMerchantFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_lists_active_products_with_lowest_marketplace_price(): void
    {
        $category = Category::create([
            'name' => 'RC',
            'slug' => 'rc-oyuncak',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'INWELT Test Hoparlör',
            'slug' => 'test-hoparlor',
            'summary' => 'Akıllı sohbet hoparlörü',
            'description' => '<p>Test açıklama</p>',
            'cover_image' => 'products/test-hoparlor/g1.webp',
            'price' => 4000.00,
            'trendyol_price' => 3750.00,
            'hepsiburada_price' => 3900.00,
            'prices_synced_at' => now(),
            'sku' => 'INWELT-TEST-HOP',
            'gtin13' => '8680000000001',
            'is_active' => true,
            'sort' => 0,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Pasif Ürün',
            'slug' => 'pasif-urun',
            'price' => 100.00,
            'is_active' => false,
            'sort' => 1,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Fiyatsız',
            'slug' => 'fiyatsiz-urun',
            'is_active' => true,
            'sort' => 2,
        ]);

        $this->get('/feed/google-merchant.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('xmlns:g="http://base.google.com/ns/1.0"', false)
            ->assertSee('<g:id>INWELT-TEST-HOP</g:id>', false)
            ->assertSee('<g:price>3750.00 TRY</g:price>', false)
            ->assertSee('<g:brand>INWELT</g:brand>', false)
            ->assertSee('<g:gtin>8680000000001</g:gtin>', false)
            ->assertSee('/urun/test-hoparlor', false)
            ->assertDontSee('pasif-urun', false)
            ->assertDontSee('fiyatsiz-urun', false)
            ->assertSee('<g:identifier_exists>yes</g:identifier_exists>', false);
    }

    public function test_feed_excludes_stale_marketplace_prices_and_marks_missing_gtin(): void
    {
        $category = Category::create([
            'name' => 'RC',
            'slug' => 'rc-oyuncak-2',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Taze Fiyat',
            'slug' => 'taze-fiyat',
            'price' => 1000.00,
            'prices_synced_at' => now(),
            'sku' => 'INWELT-TAZE',
            'is_active' => true,
            'sort' => 0,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Eski Fiyat',
            'slug' => 'eski-fiyat',
            'price' => 2000.00,
            'prices_synced_at' => now()->subDays(10),
            'sku' => 'INWELT-ESKI',
            'is_active' => true,
            'sort' => 1,
        ]);

        $this->get('/feed/google-merchant.xml')
            ->assertOk()
            ->assertSee('taze-fiyat', false)
            ->assertSee('<g:identifier_exists>false</g:identifier_exists>', false)
            ->assertDontSee('eski-fiyat', false);
    }
}

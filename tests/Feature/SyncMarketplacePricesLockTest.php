<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncMarketplacePricesLockTest extends TestCase
{
    use RefreshDatabase;

    public function test_locked_product_prices_are_not_overwritten_by_sync(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-lock',
            'sort' => 0,
            'is_active' => true,
        ]);

        $locked = Product::create([
            'category_id' => $category->id,
            'name' => 'Kilitli Ürün',
            'slug' => 'kilitli-urun',
            'seller_url' => 'https://kacmasa.com/kilitli-urun',
            'price' => 1000.00,
            'prices_locked' => true,
            'prices_synced_at' => now()->subDay(),
            'is_active' => true,
            'sort' => 0,
        ]);

        $unlocked = Product::create([
            'category_id' => $category->id,
            'name' => 'Açık Ürün',
            'slug' => 'acik-urun',
            'seller_url' => 'https://kacmasa.com/acik-urun',
            'price' => 2000.00,
            'prices_locked' => false,
            'prices_synced_at' => now()->subDay(),
            'is_active' => true,
            'sort' => 1,
        ]);

        Http::fake([
            'https://kacmasa.com/magaza/NWELT*' => Http::response('<html></html>', 200),
            'https://kacmasa.com/kilitli-urun*' => Http::response(
                '<html><span class="price">9999 TL</span></html>',
                200
            ),
            'https://kacmasa.com/acik-urun*' => Http::response(
                '<html><meta property="product:price:amount" content="2500"/></html>',
                200
            ),
        ]);

        $this->artisan('inwelt:sync-marketplace-prices', ['--pages' => 1])
            ->assertSuccessful();

        $this->assertSame('1000.00', $locked->fresh()->price);
        $this->assertTrue($locked->fresh()->prices_locked);
    }

    public function test_unlocked_product_with_existing_price_is_refreshed_from_product_page(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-refresh',
            'sort' => 0,
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Guncellenecek',
            'slug' => 'guncellenecek',
            'seller_url' => 'https://kacmasa.com/guncellenecek',
            'price' => 1500.00,
            'prices_locked' => false,
            'is_active' => true,
            'sort' => 0,
        ]);

        Http::fake([
            'https://kacmasa.com/magaza/NWELT*' => Http::response('<html></html>', 200),
            'https://kacmasa.com/guncellenecek*' => Http::response(
                $this->kacmasaProductHtml(2750.50),
                200
            ),
        ]);

        $this->artisan('inwelt:sync-marketplace-prices', ['--pages' => 1])
            ->assertSuccessful();

        $this->assertSame('2750.50', $product->fresh()->price);
    }

    private function kacmasaProductHtml(float $price): string
    {
        $formatted = number_format($price, 2, ',', '.');

        return <<<HTML
        <html>
          <script type="application/ld+json">
            {"@type":"Product","offers":{"@type":"Offer","price":"{$formatted}","priceCurrency":"TRY"}}
          </script>
        </html>
        HTML;
    }
}

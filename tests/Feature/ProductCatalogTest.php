<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_product_is_visible_on_all_products_page(): void
    {
        $category = Category::create([
            'name' => 'Akıllı Sistemler',
            'slug' => 'akilli-sistemler',
            'sort' => 0,
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Deneme',
            'slug' => 'deneme',
            'summary' => 'Test ürünü',
            'is_active' => true,
            'sort' => 0,
        ]);

        $this->get('/urunler')
            ->assertOk()
            ->assertSee($product->name);

        $this->get('/urunler?kategori=akilli-sistemler')
            ->assertOk()
            ->assertSee($product->name);

        $this->get('/kategori/akilli-sistemler')
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_product_listing_pages_are_not_cached_at_cdn_level(): void
    {
        $response = $this->get('/urunler');

        $response->assertOk();
        $this->assertFalse(
            str_contains((string) $response->headers->get('Cache-Control'), 's-maxage=3600'),
            'Ürün listesi CDN önbelleğine alınmamalı.'
        );
    }
}

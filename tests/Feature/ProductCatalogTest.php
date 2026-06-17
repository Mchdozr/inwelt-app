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

    public function test_filter_chips_return_matching_products(): void
    {
        $category = Category::create([
            'name' => 'Akıllı Cihazlar',
            'slug' => 'akilli-cihazlar',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Fırsat Ürünü',
            'slug' => 'firsat-urunu',
            'summary' => 'Test',
            'tags' => ['deal', 'bestseller'],
            'is_advantageous' => true,
            'is_active' => true,
            'sort' => 0,
        ]);

        $this->get('/urunler?filtre=deal')
            ->assertOk()
            ->assertSee('Fırsat Ürünü');

        $this->get('/urunler?filtre=bestseller')
            ->assertOk()
            ->assertSee('Fırsat Ürünü');

        $this->get('/urunler?avantajli=1')
            ->assertOk()
            ->assertSee('Fırsat Ürünü');

        $this->get('/urunler?ara=F%C4%B1rsat')
            ->assertOk()
            ->assertSee('Fırsat Ürünü');
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

    public function test_products_listing_uses_infinite_scroll_instead_of_pagination(): void
    {
        $category = Category::create([
            'name' => 'Test Kategori',
            'slug' => 'test-kategori',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Tek Ürün',
            'slug' => 'tek-urun',
            'summary' => 'Test',
            'is_active' => true,
            'sort' => 0,
        ]);

        $this->get('/urunler')
            ->assertOk()
            ->assertSee('data-infinite-scroll', false)
            ->assertDontSee('pagination', false);
    }

    public function test_grid_items_partial_returns_json_for_infinite_scroll(): void
    {
        $category = Category::create([
            'name' => 'Liste Kategori',
            'slug' => 'liste-kategori',
            'sort' => 0,
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 13; $i++) {
            Product::create([
                'category_id' => $category->id,
                'name' => "Ürün {$i}",
                'slug' => "urun-{$i}",
                'summary' => 'Test',
                'is_active' => true,
                'sort' => $i,
            ]);
        }

        $response = $this->getJson('/urunler?partial=products-grid-items&page=2');

        $response->assertOk()
            ->assertJsonStructure(['html', 'current_page', 'has_more'])
            ->assertJsonPath('current_page', 2)
            ->assertJsonPath('has_more', false);

        $this->assertStringContainsString('Ürün 13', $response->json('html'));
        $this->assertStringNotContainsString('Ürün 1', $response->json('html'));
    }
}

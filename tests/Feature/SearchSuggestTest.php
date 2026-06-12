<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSuggestTest extends TestCase
{
    use RefreshDatabase;

    public function test_suggest_returns_empty_for_short_query(): void
    {
        $this->getJson('/api/search/suggest?q=a')
            ->assertOk()
            ->assertJson([
                'query' => 'a',
                'products' => [],
            ]);
    }

    public function test_suggest_returns_matching_products(): void
    {
        $category = Category::create([
            'name' => 'Akıllı Cihazlar',
            'slug' => 'akilli-cihazlar',
            'sort' => 0,
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Akıllı Takip Cihazı',
            'slug' => 'akilli-takip-cihazi',
            'summary' => 'Bluetooth menzilli takip etiketi',
            'is_active' => true,
            'sort' => 0,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Başka Ürün',
            'slug' => 'baska-urun',
            'summary' => 'Farklı ürün',
            'is_active' => true,
            'sort' => 1,
        ]);

        $this->getJson('/api/search/suggest?q=Akıllı')
            ->assertOk()
            ->assertJsonPath('query', 'Akıllı')
            ->assertJsonCount(1, 'products')
            ->assertJsonPath('products.0.name', $product->name)
            ->assertJsonPath('products.0.slug', $product->slug)
            ->assertJsonPath('products.0.category', 'Akıllı Cihazlar')
            ->assertJsonPath('products.0.price', null);
    }

    public function test_suggest_excludes_inactive_products(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Gizli Ürün',
            'slug' => 'gizli-urun',
            'summary' => 'Pasif',
            'is_active' => false,
            'sort' => 0,
        ]);

        $this->getJson('/api/search/suggest?q=Gizli')
            ->assertOk()
            ->assertJsonCount(0, 'products');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Product;
use App\Models\User;
use App\Support\Schema\SchemaBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_layout_includes_organization_and_website_schema(): void
    {
        $this->get('/anasayfa')
            ->assertOk()
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"@type":"WebSite"', false)
            ->assertSee('SearchAction', false)
            ->assertSee('https://schema.org', false);
    }

    public function test_product_page_includes_aggregate_offer_and_breadcrumb(): void
    {
        $category = Category::create([
            'name' => 'Müzik',
            'slug' => 'muzik',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'INWELT Davul',
            'slug' => 'inwelt-davul',
            'summary' => 'Taşınabilir davul',
            'seller_url' => 'https://kacmasa.com/davul',
            'price' => 1299.00,
            'trendyol_price' => 1349.50,
            'hepsiburada_price' => 1399.00,
            'prices_synced_at' => now(),
            'sku' => 'INWELT-DAVUL',
            'rating_value' => 4.7,
            'rating_count' => 42,
            'is_active' => true,
            'sort' => 0,
        ]);

        $html = $this->get('/urun/inwelt-davul')->assertOk()->getContent();

        $this->assertStringContainsString('AggregateOffer', $html);
        $this->assertStringContainsString('BreadcrumbList', $html);
        $this->assertStringContainsString('"offers"', $html);
        $this->assertStringContainsString('1299.00', $html);
        $this->assertStringContainsString('1399.00', $html);
        $this->assertStringContainsString('INWELT-DAVUL', $html);
        $this->assertStringContainsString('AggregateRating', $html);
    }

    public function test_schema_builder_skips_stale_prices(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-stale',
            'sort' => 0,
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Eski Fiyat',
            'slug' => 'eski-fiyat',
            'price' => 100,
            'prices_synced_at' => now()->subDays(20),
            'is_active' => true,
            'sort' => 0,
        ]);

        $schema = SchemaBuilder::product($product);

        $this->assertArrayNotHasKey('offers', $schema);
    }

    public function test_category_page_includes_item_list_schema(): void
    {
        $category = Category::create([
            'name' => 'Akıllı Cihazlar',
            'slug' => 'akilli-cihazlar',
            'seo_content' => str_repeat('Kategori içeriği. ', 80),
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Smart Tag',
            'slug' => 'smart-tag',
            'is_active' => true,
            'sort' => 0,
        ]);

        $this->get('/kategori/akilli-cihazlar')
            ->assertOk()
            ->assertSee('ItemList', false)
            ->assertSee('CollectionPage', false)
            ->assertSee('Kategori içeriği.', false);
    }

    public function test_guide_page_includes_article_schema(): void
    {
        $author = User::factory()->create([
            'name' => 'Editör',
            'slug' => 'editor',
            'bio' => 'Ürün editörü',
        ]);

        Guide::create([
            'slug' => 'rc-oyuncak-secimi',
            'title' => 'Çocuklar için RC oyuncak seçimi',
            'excerpt' => 'Yaş ve ölçeğe göre seçim.',
            'body' => '<p>'.str_repeat('RC oyuncak seçerken ölçek ve güvenlik önemlidir. ', 40).'</p>',
            'author_id' => $author->id,
            'is_active' => true,
            'published_at' => now(),
        ]);

        $this->get('/rehberler/rc-oyuncak-secimi')
            ->assertOk()
            ->assertSee('Article', false)
            ->assertSee('BlogPosting', false)
            ->assertSee('Çocuklar için RC oyuncak seçimi');
    }
}

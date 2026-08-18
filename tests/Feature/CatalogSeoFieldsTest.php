<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeoFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_upsert_persists_unique_seo_faq_and_related_guides_for_j1_max(): void
    {
        Category::create([
            'name' => 'Müzik & Eğlence',
            'slug' => 'muzik-eglence',
            'sort' => 0,
            'is_active' => true,
        ]);

        $this->artisan('inwelt:upsert-catalog-product', [
            'slug' => 'j1-max-akilli-sohbet-hoparloru',
        ])->assertSuccessful();

        $product = Product::query()->where('slug', 'j1-max-akilli-sohbet-hoparloru')->first();

        $this->assertNotNull($product);
        $this->assertNotSame($product->name.' | INWELT', $product->seo_title);
        $this->assertStringContainsString('J1-MAX', $product->seo_title);
        $this->assertGreaterThanOrEqual(120, mb_strlen((string) $product->seo_description));
        $this->assertIsArray($product->faq_items);
        $this->assertGreaterThanOrEqual(4, count($product->faq_items));
        $this->assertContains('akilli-hoparlor-vs-bluetooth', $product->related_guide_slugs ?? []);
        $this->assertGreaterThan(400, str_word_count(strip_tags((string) $product->description)));
    }
}

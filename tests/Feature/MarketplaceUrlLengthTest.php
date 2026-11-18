<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MarketplaceUrlLengthTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketplace_url_columns_store_more_than_500_characters(): void
    {
        foreach (['seller_url', 'trendyol_url', 'hepsiburada_url'] as $column) {
            $this->assertContains(
                Schema::getColumnType('products', $column),
                ['text', 'longtext', 'mediumtext', 'clob'],
                "{$column} should be TEXT, not VARCHAR(500)"
            );
        }

        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'sort' => 0,
            'is_active' => true,
        ]);

        $long = 'https://www.trendyol.com/'.str_repeat('x', 700);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Uzun URL',
            'slug' => 'uzun-url',
            'summary' => 'Test',
            'seller_url' => $long.'-kacmasa',
            'trendyol_url' => $long.'-ty',
            'hepsiburada_url' => $long.'-hb',
            'is_active' => true,
            'sort' => 0,
        ]);

        $product->refresh();

        $this->assertGreaterThan(500, strlen($product->trendyol_url));
        $this->assertSame($long.'-ty', $product->trendyol_url);
        $this->assertSame($long.'-hb', $product->hepsiburada_url);
        $this->assertSame($long.'-kacmasa', $product->seller_url);
    }

    public function test_marketplace_url_form_fields_are_not_capped_at_500_characters(): void
    {
        $source = file_get_contents(base_path('app/Filament/Resources/Products/Schemas/ProductForm.php'));
        $this->assertNotFalse($source);

        foreach (['seller_url', 'trendyol_url', 'hepsiburada_url'] as $field) {
            $this->assertDoesNotMatchRegularExpression(
                '/TextInput::make\(\''.$field.'\'\)(?:(?!TextInput::make\().)*->maxLength\(500\)/s',
                $source,
                "{$field} still has maxLength(500)"
            );
        }
    }
}

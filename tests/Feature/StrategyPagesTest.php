<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StrategyPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_page_renders_with_schema(): void
    {
        $this->get('/sss')
            ->assertOk()
            ->assertSee('INWELT üzerinden doğrudan satın alabilir miyim?')
            ->assertSee('FAQPage', false);
    }

    public function test_guides_pages_are_available(): void
    {
        $this->get('/rehberler')
            ->assertOk()
            ->assertSee('RC oyuncak seçimi');

        $this->get('/rehberler/rc-oyuncak-secimi')
            ->assertOk()
            ->assertSee('Çocuklar için RC oyuncak seçimi');
    }

    public function test_contact_form_sends_notification_mail(): void
    {
        Mail::fake();

        Setting::put('site_email', 'inwelt@inwelt.com.tr');

        $this->post('/iletisim', [
            'name' => 'Test Kullanıcı',
            'email' => 'test@example.com',
            'message' => 'Merhaba, bilgi almak istiyorum.',
        ])->assertRedirect();

        Mail::assertSent(ContactMessageReceived::class);
    }

    public function test_product_detail_shows_marketplace_prices_under_buttons(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-kat',
            'sort' => 0,
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Fiyatlı Ürün',
            'slug' => 'fiyatli-urun',
            'seller_url' => 'https://kacmasa.com/fiyatli-urun',
            'price' => 1299.00,
            'trendyol_price' => 1349.50,
            'hepsiburada_price' => 1399.00,
            'is_active' => true,
            'sort' => 0,
        ]);

        $this->get('/urun/fiyatli-urun')
            ->assertOk()
            ->assertSee('1.299,00', false)
            ->assertSee('1.349,50', false)
            ->assertSee('1.399,00', false)
            ->assertSee('marketplace-buttons__price-amount', false)
            ->assertSee('marketplace-buttons__price-currency', false)
            ->assertSee('marketplace-buttons__price', false)
            ->assertSee('data-track-marketplace="kacmasa"', false)
            ->assertDontSee('"offers"', false);
    }

    public function test_product_detail_hides_missing_marketplace_prices(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-kat-2',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Fiyatsız Ürün',
            'slug' => 'fiyatsiz-urun',
            'is_active' => true,
            'sort' => 0,
        ]);

        $this->get('/urun/fiyatsiz-urun')
            ->assertOk()
            ->assertDontSee('marketplace-buttons__price', false)
            ->assertSee('data-track-marketplace="trendyol"', false);
    }

    public function test_product_detail_shows_pending_label_when_kacmasa_price_missing(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-kat-3',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Bekleyen Fiyatlı Ürün',
            'slug' => 'bekleyen-fiyatli-urun',
            'seller_url' => 'https://kacmasa.com/bekleyen-fiyatli-urun',
            'is_active' => true,
            'sort' => 0,
        ]);

        $this->get('/urun/bekleyen-fiyatli-urun')
            ->assertOk()
            ->assertSee('Fiyat güncelleniyor')
            ->assertSee('marketplace-buttons__price--pending', false);
    }
}

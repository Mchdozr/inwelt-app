<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Support\SiteContact;
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

        Setting::put('site_email', SiteContact::EMAIL);

        $this->post('/iletisim', [
            'name' => 'Test Kullanıcı',
            'email' => 'test@example.com',
            'message' => 'Merhaba, bilgi almak istiyorum.',
        ])->assertRedirect();

        Mail::assertSent(ContactMessageReceived::class);
    }

    public function test_contact_page_shows_current_phone_and_email(): void
    {
        Setting::put('site_phone', SiteContact::PHONE);
        Setting::put('site_email', SiteContact::EMAIL);

        $this->get('/iletisim')
            ->assertOk()
            ->assertSee(SiteContact::PHONE, false)
            ->assertSee('href="'.SiteContact::telHref().'"', false)
            ->assertSee(SiteContact::EMAIL, false)
            ->assertSee('mailto:'.SiteContact::EMAIL, false)
            ->assertSee('contact-info-card', false);
    }

    public function test_footer_and_whatsapp_use_current_contact_details(): void
    {
        Setting::put('site_phone', SiteContact::PHONE);
        Setting::put('site_email', SiteContact::EMAIL);
        Setting::put('whatsapp_phone', SiteContact::PHONE);

        $this->get('/urunler')
            ->assertOk()
            ->assertSee(SiteContact::PHONE, false)
            ->assertSee('href="'.SiteContact::telHref().'"', false)
            ->assertSee('mailto:'.SiteContact::EMAIL, false)
            ->assertSee('https://wa.me/905433594002', false)
            ->assertSee('whatsapp-float', false);
    }

    public function test_legacy_contact_settings_migration_updates_old_values(): void
    {
        Setting::put('site_phone', '+90 850 000 00 00');
        Setting::put('whatsapp_phone', '+90 549 800 25 10');
        Setting::put('site_email', 'info@inwelt.com.tr');

        $migration = require database_path('migrations/2026_06_18_120000_update_legacy_contact_settings.php');
        $migration->up();

        $this->assertSame(SiteContact::PHONE, Setting::get('site_phone'));
        $this->assertSame(SiteContact::PHONE, Setting::get('whatsapp_phone'));
        $this->assertSame(SiteContact::EMAIL, Setting::get('site_email'));
    }

    public function test_home_hero_shows_marketplace_float_rail(): void
    {
        $this->get('/anasayfa')
            ->assertOk()
            ->assertSee('hero-marketplace-dock', false)
            ->assertSee('kacmasa.com/magaza/NWELT', false)
            ->assertSee('data-track-marketplace="trendyol"', false)
            ->assertSee('data-track-marketplace="hepsiburada"', false);
    }

    public function test_product_detail_shows_marketplace_buttons_without_prices(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-kat',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
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
            ->assertSee('marketplace-buttons', false)
            ->assertSee('btn-marketplace', false)
            ->assertSee('kacmasa.com/fiyatli-urun', false)
            ->assertDontSee('kacmasa.com/magaza/NWELT', false)
            ->assertDontSee('marketplace-float-rail', false)
            ->assertSee('data-track-marketplace="kacmasa"', false)
            ->assertSee('data-track-marketplace="trendyol"', false)
            ->assertSee('data-track-marketplace="hepsiburada"', false)
            ->assertDontSee('1.299,00', false)
            ->assertDontSee('marketplace-buttons__price', false)
            ->assertDontSee('"offers"', false);
    }

    public function test_product_detail_shows_marketplace_buttons_when_prices_missing(): void
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
}

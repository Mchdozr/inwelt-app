<?php

namespace Tests\Unit;

use App\Models\Setting;
use App\Support\SiteContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_tel_href_strips_spaces_and_formats_e164(): void
    {
        $this->assertSame('tel:+905433594002', SiteContact::telHref('+90 543 359 40 02'));
    }

    public function test_phone_and_email_use_defaults_when_settings_missing(): void
    {
        $this->assertSame(SiteContact::PHONE, SiteContact::phone());
        $this->assertSame(SiteContact::EMAIL, SiteContact::email());
    }

    public function test_phone_and_email_read_from_settings(): void
    {
        Setting::put('site_phone', '+90 555 111 22 33');
        Setting::put('site_email', 'test@example.com');

        $this->assertSame('+90 555 111 22 33', SiteContact::phone());
        $this->assertSame('test@example.com', SiteContact::email());
    }

    public function test_legacy_phone_and_email_are_normalized(): void
    {
        Setting::put('site_phone', '+90 850 000 00 00');
        Setting::put('site_email', 'info@inwelt.com.tr');

        $this->assertSame(SiteContact::PHONE, SiteContact::phone());
        $this->assertSame(SiteContact::EMAIL, SiteContact::email());
    }

    public function test_sync_settings_updates_legacy_values(): void
    {
        Setting::put('site_phone', '+90 850 000 00 00');
        Setting::put('whatsapp_phone', '+90 549 800 25 10');
        Setting::put('site_email', 'info@inwelt.com.tr');

        SiteContact::syncSettings();

        $this->assertSame(SiteContact::PHONE, Setting::get('site_phone'));
        $this->assertSame(SiteContact::PHONE, Setting::get('whatsapp_phone'));
        $this->assertSame(SiteContact::EMAIL, Setting::get('site_email'));
    }
}

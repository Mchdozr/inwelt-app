<?php

namespace Tests\Unit;

use App\Support\WhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_url_uses_default_phone_when_settings_missing(): void
    {
        $this->assertStringStartsWith('https://wa.me/905433594002', WhatsApp::url());
    }

    public function test_url_includes_encoded_message_when_provided(): void
    {
        $url = WhatsApp::url('Merhaba');

        $this->assertStringContainsString('https://wa.me/905433594002?text=', $url);
        $this->assertStringContainsString(rawurlencode('Merhaba'), $url);
    }
}

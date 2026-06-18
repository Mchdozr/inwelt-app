<?php

namespace App\Support;

use App\Models\Setting;

final class WhatsApp
{
    public static function phoneE164(): string
    {
        $raw = Setting::get('whatsapp_phone') ?: Setting::get('site_phone') ?: SiteContact::PHONE;
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = '90'.substr($digits, 1);
        }

        if (! str_starts_with($digits, '90') && strlen($digits) === 10) {
            $digits = '90'.$digits;
        }

        return $digits;
    }

    public static function url(?string $message = null): string
    {
        $base = 'https://wa.me/'.self::phoneE164();

        if ($message === null || $message === '') {
            return $base;
        }

        return $base.'?text='.rawurlencode($message);
    }

    public static function productMessage(string $productName, string $productUrl): string
    {
        return "Merhaba, INWELT sitesinden yazıyorum. \"{$productName}\" ürünü hakkında bilgi almak istiyorum.\n\n{$productUrl}";
    }
}

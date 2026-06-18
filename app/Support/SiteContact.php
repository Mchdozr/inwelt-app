<?php

namespace App\Support;

use App\Models\Setting;

final class SiteContact
{
    public const PHONE = '+90 543 359 40 02';

    public const EMAIL = 'inwelt@inwelt.com.tr';

    /** @var list<string> */
    private const LEGACY_PHONE_DIGITS = [
        '908500000000',
        '905498002510',
        '8500000000',
        '5498002510',
    ];

    public static function phone(): string
    {
        $value = Setting::get('site_phone');

        if ($value === null || $value === '' || self::isLegacyPhone($value)) {
            return self::PHONE;
        }

        return $value;
    }

    public static function email(): string
    {
        $value = Setting::get('site_email');

        if ($value === null || $value === '' || strtolower($value) === 'info@inwelt.com.tr') {
            return self::EMAIL;
        }

        return $value;
    }

    public static function isLegacyPhone(string $phone): bool
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        return in_array($digits, self::LEGACY_PHONE_DIGITS, true);
    }

    public static function syncSettings(): void
    {
        $phone = Setting::get('site_phone');
        if ($phone === null || $phone === '' || self::isLegacyPhone($phone)) {
            Setting::put('site_phone', self::PHONE);
        }

        $whatsapp = Setting::get('whatsapp_phone');
        if ($whatsapp === null || $whatsapp === '' || self::isLegacyPhone($whatsapp)) {
            Setting::put('whatsapp_phone', self::PHONE);
        }

        $email = Setting::get('site_email');
        if ($email === null || $email === '' || strtolower((string) $email) === 'info@inwelt.com.tr') {
            Setting::put('site_email', self::EMAIL);
        }
    }

    public static function telHref(?string $phone = null): string
    {
        $digits = preg_replace('/\D+/', '', $phone ?? self::phone()) ?? '';

        return 'tel:+'.$digits;
    }
}

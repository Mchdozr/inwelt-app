<?php

namespace App\Support;

final class SeoEnv
{
    public const INDEXNOW_DEFAULT = 'a7c4e19b6d2f4803ae5c91d8b0f47e26';

    public static function verification(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        if (preg_match('/kodu|karakter|XXXX|placeholder|example/i', $value)) {
            return null;
        }

        return $value;
    }

    public static function indexNowKey(?string $value): ?string
    {
        $value = self::verification($value);

        if ($value === null || ! preg_match('/^[A-Za-z0-9]{8,64}$/', $value)) {
            return null;
        }

        return $value;
    }
}

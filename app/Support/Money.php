<?php

namespace App\Support;

final class Money
{
    public static function formatTry(float|string|null $amount): ?string
    {
        if ($amount === null || $amount === '') {
            return null;
        }

        $value = (float) $amount;

        if ($value <= 0) {
            return null;
        }

        return number_format($value, 2, ',', '.').' ₺';
    }

    public static function parseTurkish(string $raw): ?float
    {
        $normalized = str_replace('.', '', trim($raw));
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? (float) $normalized : null;
    }
}

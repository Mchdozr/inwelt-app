<?php

namespace App\Services;

use App\Support\Money;

final class MarketplacePriceParser
{
    public function parseKacmasaHtml(string $html): ?float
    {
        if (preg_match('#"price"\s*:\s*"?([\d.,]+)"?#u', $html, $match)) {
            $parsed = Money::parseTurkish($match[1]);

            if ($parsed !== null) {
                return $parsed;
            }
        }

        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        preg_match_all('#(\d{1,3}(?:\.\d{3})*,\d{2})\s*TL#u', $text, $matches);

        if (empty($matches[1])) {
            return null;
        }

        $amounts = array_values(array_filter(
            array_map(fn (string $raw) => Money::parseTurkish($raw), $matches[1]),
            fn (?float $value) => $value !== null && $value > 0,
        ));

        return $amounts === [] ? null : min($amounts);
    }

    public function parseTrendyolHtml(string $html): ?float
    {
        $patterns = [
            '#"sellingPrice"\s*:\s*([\d.]+)#',
            '#"discountedPrice"\s*:\s*\{[^}]*"value"\s*:\s*([\d.]+)#',
            '#"price"\s*:\s*\{[^}]*"sellingPrice"\s*:\s*([\d.]+)#',
            '#"price"\s*:\s*([\d.]+)#',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                $parsed = (float) $match[1];

                if ($parsed > 0) {
                    return $parsed;
                }
            }
        }

        return $this->parseTurkishLiraFromText($html);
    }

    public function parseHepsiburadaHtml(string $html): ?float
    {
        $patterns = [
            '#"price"\s*:\s*"([\d.,]+)"#',
            '#"price"\s*:\s*([\d.]+)#',
            '#"currentPrice"\s*:\s*([\d.]+)#',
            '#data-price="([\d.,]+)"#',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                $parsed = $this->parseNumericPrice($match[1]);

                if ($parsed !== null && $parsed > 0) {
                    return $parsed;
                }
            }
        }

        return $this->parseTurkishLiraFromText($html);
    }

    private function parseNumericPrice(string $raw): ?float
    {
        if (is_numeric($raw)) {
            return (float) $raw;
        }

        return Money::parseTurkish($raw);
    }

    private function parseTurkishLiraFromText(string $html): ?float
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        preg_match_all('#(\d{1,3}(?:\.\d{3})*,\d{2})\s*(?:TL|₺)#u', $text, $matches);

        if (empty($matches[1])) {
            return null;
        }

        $amounts = array_values(array_filter(
            array_map(fn (string $raw) => Money::parseTurkish($raw), $matches[1]),
            fn (?float $value) => $value !== null && $value > 0,
        ));

        return $amounts === [] ? null : min($amounts);
    }
}

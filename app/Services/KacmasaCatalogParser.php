<?php

namespace App\Services;

use App\Support\Money;

final class KacmasaCatalogParser
{
    /**
     * @return list<array{url: string, name: string, price: ?float, compare_at_price: ?float}>
     */
    public function parseListingHtml(string $html): array
    {
        if (! preg_match_all(
            '#<div class="product-layout[^"]*">(.*?)</div>\s*</div>\s*</div>#s',
            $html,
            $blocks
        )) {
            return [];
        }

        $items = [];

        foreach ($blocks[1] as $block) {
            if (! preg_match('#href="(https://kacmasa\.com/[^"]+)"#', $block, $urlMatch)) {
                continue;
            }

            if (! preg_match('#<h4><a[^>]+>([^<]+)</a>#', $block, $nameMatch)) {
                preg_match('#alt="([^"]+)"#', $block, $nameMatch);
            }

            $name = trim(html_entity_decode($nameMatch[1] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $url = $this->normalizeUrl(html_entity_decode($urlMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            [$price, $compareAt] = $this->parsePricesFromBlock($block);

            $items[] = [
                'url' => $url,
                'name' => $name,
                'price' => $price,
                'compare_at_price' => $compareAt,
            ];
        }

        return $items;
    }

    public function normalizeUrl(string $url): string
    {
        $url = rtrim($url, '/');

        return preg_replace('#\?.*$#', '', $url) ?? $url;
    }

    /**
     * @return array{0: ?float, 1: ?float}
     */
    private function parsePricesFromBlock(string $block): array
    {
        $text = strip_tags($block);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        preg_match_all('#(\d{1,3}(?:\.\d{3})*,\d{2})\s*TL#u', $text, $matches);

        if (empty($matches[1])) {
            return [null, null];
        }

        $amounts = array_map(fn (string $raw) => Money::parseTurkish($raw), $matches[1]);
        $amounts = array_values(array_filter($amounts, fn (?float $v) => $v !== null));

        if ($amounts === []) {
            return [null, null];
        }

        $price = min($amounts);
        $compareAt = count($amounts) > 1 ? max($amounts) : null;

        if ($compareAt !== null && $compareAt <= $price) {
            $compareAt = null;
        }

        return [$price, $compareAt];
    }
}

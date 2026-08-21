<?php

namespace Tests\Unit;

use App\Services\MarketplacePriceParser;
use App\Support\Money;
use Tests\TestCase;

class MarketplacePriceParserTest extends TestCase
{
    public function test_money_formats_turkish_lira(): void
    {
        $this->assertSame('1.299,00 ₺', Money::formatTry(1299));
        $this->assertNull(Money::formatTry(null));
    }

    public function test_trendyol_parser_reads_json_price(): void
    {
        $html = '<script>{"sellingPrice":1499.99,"currency":"TRY"}</script>';

        $this->assertSame(1499.99, (new MarketplacePriceParser)->parseTrendyolHtml($html));
    }

    public function test_hepsiburada_parser_reads_json_price(): void
    {
        $html = '<script>{"currentPrice":899.5}</script>';

        $this->assertSame(899.5, (new MarketplacePriceParser)->parseHepsiburadaHtml($html));
    }

    public function test_kacmasa_parser_reads_international_json_price(): void
    {
        $html = '<script type="application/ld+json">{"@type":"Product","offers":{"price":"1399.00","priceCurrency":"TRY"}}</script>';

        $this->assertSame(1399.0, (new MarketplacePriceParser)->parseKacmasaHtml($html));
    }

    public function test_kacmasa_parser_reads_turkish_text_price(): void
    {
        $html = '<div class="price">1.399,00 TL</div>';

        $this->assertSame(1399.0, (new MarketplacePriceParser)->parseKacmasaHtml($html));
    }
}

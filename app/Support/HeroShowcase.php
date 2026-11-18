<?php

namespace App\Support;

class HeroShowcase
{
    /**
     * @var list<array{src: string, alt: string}>
     */
    private const IMAGES = [
        [
            'src' => 'images/hero/hero-composite.png',
            'alt' => 'INWELT akıllı cihaz, şarj istasyonu ve RC ürün vitrini',
        ],
    ];

    /**
     * @return array{src: string, alt: string}
     */
    public static function random(): array
    {
        return self::IMAGES[array_rand(self::IMAGES)];
    }
}

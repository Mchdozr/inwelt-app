<?php

namespace Tests\Unit;

use App\Support\HeroShowcase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HeroShowcaseTest extends TestCase
{
    #[Test]
    public function random_returns_valid_hero_image(): void
    {
        $hero = HeroShowcase::random();

        $this->assertArrayHasKey('src', $hero);
        $this->assertArrayHasKey('alt', $hero);
        $this->assertFileExists(public_path($hero['src']));
        $this->assertNotEmpty($hero['alt']);
    }
}

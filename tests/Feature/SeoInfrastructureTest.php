<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Product;
use App\Models\User;
use App\Support\IndexNow;
use App\Support\OutboundLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SeoInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_outbound_links_use_marketplace_referral_utm(): void
    {
        $url = OutboundLink::withUtm('https://kacmasa.com/urun', 'kacmasa', 'dijital-davul');

        $this->assertStringContainsString('utm_source=inwelt', $url);
        $this->assertStringContainsString('utm_medium=marketplace_referral', $url);
        $this->assertStringContainsString('utm_campaign=dijital-davul', $url);
        $this->assertStringContainsString('utm_content=kacmasa', $url);
    }

    public function test_robots_allows_ai_crawlers_and_points_to_sitemap_index(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk()
            ->assertSee('User-agent: GPTBot', false)
            ->assertSee('User-agent: ClaudeBot', false)
            ->assertSee('User-agent: PerplexityBot', false)
            ->assertSee('User-agent: Google-Extended', false)
            ->assertSee('User-agent: OAI-SearchBot', false)
            ->assertSee('Disallow: /admin', false)
            ->assertSee('/sitemap.xml', false);
    }

    public function test_sitemap_index_lists_split_sitemaps(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('sitemapindex', false)
            ->assertSee('/sitemap-pages.xml', false)
            ->assertSee('/sitemap-products.xml', false)
            ->assertSee('/sitemap-categories.xml', false)
            ->assertSee('/sitemap-guides.xml', false);
    }

    public function test_llms_txt_describes_the_brand(): void
    {
        $this->get('/llms.txt')
            ->assertOk()
            ->assertSee('INWELT', false)
            ->assertSee('inwelt.com.tr', false);
    }

    public function test_legal_and_trust_pages_are_indexable(): void
    {
        foreach ([
            '/hakkimizda',
            '/editoryal-politika',
            '/gizlilik-politikasi',
            '/kvkk-aydinlatma',
            '/kullanim-sartlari',
            '/cerez-politikasi',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_filtered_catalog_canonical_drops_query_string(): void
    {
        $html = $this->get('/urunler?filtre=deal')->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '#<link rel="canonical" href="[^"]+/urunler">#',
            $html,
        );
    }

    public function test_security_headers_are_present_on_public_pages(): void
    {
        $response = $this->get('/anasayfa');

        $response->assertOk();
        $this->assertNotEmpty($response->headers->get('X-Content-Type-Options'));
        $this->assertNotEmpty($response->headers->get('Referrer-Policy'));
        $this->assertNotEmpty($response->headers->get('X-Frame-Options'));
    }

    public function test_indexnow_pings_on_product_save(): void
    {
        config(['seo.indexnow_key' => 'test-indexnow-key']);
        Http::fake();

        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test-seo',
            'sort' => 0,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'IndexNow Ürün',
            'slug' => 'indexnow-urun',
            'is_active' => true,
            'sort' => 0,
        ]);

        Http::assertSent(fn ($request) => str_contains($request->url(), 'api.indexnow.org'));
    }

    public function test_indexnow_key_file_is_public(): void
    {
        config(['seo.indexnow_key' => 'abcdef123456']);

        $this->get('/abcdef123456.txt')
            ->assertOk()
            ->assertSee('abcdef123456', false);
    }

    public function test_author_profile_page_renders(): void
    {
        $author = User::factory()->create([
            'name' => 'Ayşe Uzman',
            'slug' => 'ayse-uzman',
            'bio' => 'INWELT ürün editörü.',
            'expertise' => ['akıllı cihazlar', 'RC oyuncak'],
        ]);

        $this->get('/yazar/ayse-uzman')
            ->assertOk()
            ->assertSee('Ayşe Uzman')
            ->assertSee('Person', false);
    }

    public function test_orphan_audit_command_runs(): void
    {
        $this->artisan('inwelt:seo-orphan-audit')->assertSuccessful();
    }

    public function test_indexnow_client_builds_payload(): void
    {
        config(['seo.indexnow_key' => 'abcdefgh', 'app.url' => 'https://inwelt.com.tr']);

        $payload = IndexNow::payload(['https://inwelt.com.tr/urun/x']);

        $this->assertSame('abcdefgh', $payload['key']);
        $this->assertSame('inwelt.com.tr', $payload['host']);
        $this->assertContains('https://inwelt.com.tr/urun/x', $payload['urlList']);
    }

    public function test_placeholder_verification_env_is_ignored(): void
    {
        $this->assertNull(\App\Support\SeoEnv::verification('google_verification_kodu'));
        $this->assertNull(\App\Support\SeoEnv::verification('bing_verification_kodu'));
        $this->assertNull(\App\Support\SeoEnv::indexNowKey('32_karakterlik_rastgele_anahtar'));
        $this->assertSame(
            \App\Support\SeoEnv::INDEXNOW_DEFAULT,
            \App\Support\SeoEnv::indexNowKey(null) ?? \App\Support\SeoEnv::INDEXNOW_DEFAULT,
        );
    }

    public function test_indexnow_key_ignores_cached_placeholder(): void
    {
        config(['seo.indexnow_key' => '32_karakterlik_rastgele_anahtar']);

        $this->assertSame(\App\Support\SeoEnv::INDEXNOW_DEFAULT, IndexNow::key());
        $this->get('/'.\App\Support\SeoEnv::INDEXNOW_DEFAULT.'.txt')
            ->assertOk()
            ->assertSee(\App\Support\SeoEnv::INDEXNOW_DEFAULT, false);
    }
}

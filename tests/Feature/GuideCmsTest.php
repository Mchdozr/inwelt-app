<?php

namespace Tests\Feature;

use App\Models\Guide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuideCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guides_index_lists_published_guides(): void
    {
        $author = User::factory()->create(['slug' => 'editor']);

        Guide::create([
            'slug' => 'hediye-fikirleri',
            'title' => 'INWELT hediye fikirleri',
            'excerpt' => 'Pratik hediye önerileri.',
            'body' => '<p>Hediye seçerken yaş grubunu netleştirin.</p>',
            'author_id' => $author->id,
            'is_active' => true,
            'published_at' => now(),
        ]);

        $this->get('/rehberler')
            ->assertOk()
            ->assertSee('INWELT hediye fikirleri');
    }

    public function test_unpublished_guides_return_404(): void
    {
        Guide::create([
            'slug' => 'taslak',
            'title' => 'Taslak',
            'excerpt' => 'Taslak',
            'body' => '<p>Taslak</p>',
            'is_active' => false,
            'published_at' => null,
        ]);

        $this->get('/rehberler/taslak')->assertNotFound();
    }

    public function test_guides_sitemap_includes_published_guides(): void
    {
        Guide::create([
            'slug' => 'akilli-cihaz-rehberi',
            'title' => 'Akıllı cihaz alırken dikkat edilecekler',
            'excerpt' => 'Uyumluluk listesi.',
            'body' => '<p>iOS ve Android uyumu kontrol edin.</p>',
            'is_active' => true,
            'published_at' => now(),
        ]);

        $this->get('/sitemap-guides.xml')
            ->assertOk()
            ->assertSee('/rehberler/akilli-cihaz-rehberi', false);
    }

    public function test_product_buying_guides_are_indexable_when_published(): void
    {
        Guide::create([
            'slug' => 'drift-scooter-alirken',
            'title' => 'Drift scooter alırken nelere bakılır? 2026 rehber',
            'excerpt' => 'Motor watt ve güvenlik.',
            'body' => '<p>350W ve kask.</p>',
            'is_active' => true,
            'published_at' => now(),
        ]);

        Guide::create([
            'slug' => 'akilli-hoparlor-vs-bluetooth',
            'title' => 'Akıllı hoparlör mü klasik Bluetooth mu? 2026 karşılaştırma',
            'excerpt' => 'Ekran ve watt farkı.',
            'body' => '<p>J1-MAX masaüstü asistan.</p>',
            'is_active' => true,
            'published_at' => now(),
        ]);

        $this->get('/rehberler/drift-scooter-alirken')->assertOk()->assertSee('Drift scooter');
        $this->get('/rehberler/akilli-hoparlor-vs-bluetooth')->assertOk()->assertSee('Akıllı hoparlör');
    }

    public function test_admin_guide_pages_render(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/guides')
            ->assertOk()
            ->assertSee('Rehberler');
    }
}

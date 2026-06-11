<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResourcePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_category_and_product_create_pages_render(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/categories')
            ->assertOk()
            ->assertSee('Kategoriler');

        $this->actingAs($user)
            ->get('/admin/categories/create')
            ->assertOk()
            ->assertSee('Ad')
            ->assertSee('Slug');

        $this->actingAs($user)
            ->get('/admin/products')
            ->assertOk()
            ->assertSee('Ürünler');

        $this->actingAs($user)
            ->get('/admin/products/create')
            ->assertOk()
            ->assertSee('Ürün Adı')
            ->assertSee('Kategori');
    }

    public function test_admin_site_settings_page_renders(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/site-settings')
            ->assertOk()
            ->assertSee('İletişim Bilgileri')
            ->assertSee('Sosyal Medya')
            ->assertSee('Kaydet');
    }
}

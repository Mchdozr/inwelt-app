<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/urunler', 301);
Route::get('/anasayfa', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-products.xml', [SitemapController::class, 'products'])->name('sitemap.products');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-guides.xml', [SitemapController::class, 'guides'])->name('sitemap.guides');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/llms.txt', [SitemapController::class, 'llms'])->name('llms');
Route::get('/{key}.txt', [SitemapController::class, 'indexNowKey'])
    ->where('key', '[A-Za-z0-9]{8,64}')
    ->name('indexnow.key');
Route::get('/api/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/kategori/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/urun/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/hakkimizda', fn () => view('pages.about'))->name('about');
Route::get('/sss', [FaqController::class, 'index'])->name('faq');
Route::get('/rehberler', [GuideController::class, 'index'])->name('guides.index');
Route::get('/rehberler/{slug}', [GuideController::class, 'show'])->name('guides.show');
Route::get('/yazar/{slug}', [AuthorController::class, 'show'])->name('authors.show');
Route::get('/editoryal-politika', [LegalController::class, 'editorial'])->name('legal.editorial');
Route::get('/gizlilik-politikasi', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/kvkk-aydinlatma', [LegalController::class, 'kvkk'])->name('legal.kvkk');
Route::get('/kullanim-sartlari', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/cerez-politikasi', [LegalController::class, 'cookies'])->name('legal.cookies');
Route::get('/iletisim', [ContactController::class, 'show'])->name('contact');
Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store');

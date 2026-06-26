<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/urunler', 301);
Route::get('/anasayfa', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');
Route::get('/api/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/kategori/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/urun/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/hakkimizda', fn () => view('pages.about'))->name('about');
Route::get('/sss', [FaqController::class, 'index'])->name('faq');
Route::get('/rehberler', [GuideController::class, 'index'])->name('guides.index');
Route::get('/rehberler/{slug}', [GuideController::class, 'show'])->name('guides.show');
Route::get('/iletisim', [ContactController::class, 'show'])->name('contact');
Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store');

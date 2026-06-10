<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');
Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/kategori/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/urun/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/hakkimizda', fn () => view('pages.about'))->name('about');
Route::get('/iletisim', [ContactController::class, 'show'])->name('contact');
Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store');

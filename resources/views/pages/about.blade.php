@extends('layouts.app')

@section('title', 'Hakkımızda | INWELT')
@section('description', 'INWELT; İstanbul merkezli marka vitrini. Akıllı cihaz, RC oyuncak ve ev-hobi ürünlerinde şeffaf fiyat ve güvenilir pazaryeri alışverişi.')

@push('head')
<x-json-ld :data="\App\Support\Schema\SchemaBuilder::organization()" />
@endpush

@section('content')
<section class="page-hero py-16 md:py-20">
    <div class="relative site-container">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-iw-text tracking-[-0.035em] font-display reveal">INWELT: markayı tek yerde toplayan vitrin</h1>
                <p class="mt-5 text-iw-text-muted text-lg max-w-xl leading-relaxed reveal" style="--reveal-delay: 0.08s">2024’ten beri İstanbul’dan yönetilen INWELT, ürünleri karşılaştırmanıza ve Kacmasa, Trendyol ile Hepsiburada üzerinden güvenle satın almanıza yardımcı olur. Adres: {{ \App\Support\SiteContact::address() }}</p>
                <div class="mt-8 flex flex-wrap gap-3 reveal" style="--reveal-delay: 0.14s">
                    <a href="{{ route('products.index') }}" class="btn-primary">Ürünleri keşfet</a>
                    <a href="{{ route('legal.editorial') }}" class="btn-outline">Editoryal politika</a>
                </div>
            </div>
            <div class="about-hero-visual reveal" style="--reveal-delay: 0.12s">
                <img src="{{ asset('images/inwelt-logo.png') }}" alt="INWELT" class="about-hero-visual__logo" width="480" height="120" decoding="async">
            </div>
        </div>
    </div>
</section>

<section class="py-16 md:py-20 reveal">
    <div class="site-container max-w-3xl">
        <h2 class="text-2xl font-bold font-display">Kimiz?</h2>
        <p class="mt-4 text-iw-text-muted leading-relaxed">INWELT bir mağaza kasası değil, marka kataloğudur. Teknik özellikleri, kullanım alanlarını ve pazaryeri fiyat aralıklarını tek sayfada toplarız; satın alma seçtiğiniz satıcıda tamamlanır. İletişim: {{ \App\Support\SiteContact::EMAIL }}</p>
        <h2 class="text-2xl font-bold font-display mt-10">Neden INWELT?</h2>
        <ul class="mt-4 space-y-2 text-iw-text-muted">
            <li>Şeffaf fiyat aralığı (Kacmasa / Trendyol / Hepsiburada)</li>
            <li>Özgün ürün açıklamaları ve seçim rehberleri</li>
            <li>Günlük fiyat senkronu ve güncel katalog</li>
            <li>WhatsApp ve e-posta ile hızlı destek</li>
        </ul>
    </div>
</section>
@endsection

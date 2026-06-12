@extends('layouts.app')

@section('title', isset($activeCategory) ? $activeCategory->name : 'Ürünler')
@section('description', isset($activeCategory) && $activeCategory->description
    ? $activeCategory->description
    : 'INWELT ürün kataloğu. Akıllı cihazlar, RC oyuncaklar, müzik setleri, zeka oyunları ve güvenlik ürünlerini inceleyin.')

@section('content')

@php
    $activeFilterSlug = request('filtre');
    $activeFilterLabel = $activeFilterSlug && \App\Support\ProductFilters::isValid($activeFilterSlug)
        ? \App\Support\ProductFilters::LABELS[$activeFilterSlug]
        : null;
@endphp

@php
    $catalogHero = match (true) {
        filled($activeFilterLabel) => [
            'title' => $activeFilterLabel,
            'subtitle' => 'Seçili filtreye göre özenle listelenen ürünler. Yeni fırsatları kaçırmayın.',
        ],
        isset($activeCategory) => [
            'title' => $activeCategory->name,
            'subtitle' => $activeCategory->description ?: 'Bu kategorideki ürünleri inceleyin; uygun fiyat ve güvenilir alışveriş tek adreste.',
        ],
        request()->filled('ara') => [
            'title' => '“'.request('ara').'” için sonuçlar',
            'subtitle' => 'Aramanıza uygun ürünleri aşağıda bulabilirsiniz.',
        ],
        default => [
            'title' => 'Tüm Ürünler',
            'subtitle' => 'Ev, teknoloji, hobi ve hediye — geniş katalogda ihtiyacınız olanı hızlıca keşfedin.',
        ],
    };
@endphp

<section class="page-hero page-hero--catalog">
    <div class="page-hero__glow" aria-hidden="true"></div>
    <div class="relative site-container">
        <div class="catalog-hero">
            <div class="catalog-hero__copy">
                <span class="eyebrow-badge">Ürün Kataloğu</span>
                <h1>{{ $catalogHero['title'] }}</h1>
                <p>{{ $catalogHero['subtitle'] }}</p>
            </div>
            <div class="catalog-hero__orbit" aria-hidden="true">
                @foreach([
                    ['Akıllı Cihazlar', 'images/hero/charger.png', 'blue', 'top-0 left-[8%]'],
                    ['RC & Oyuncak', 'images/hero/rc-car.png', 'orange', 'top-[18%] right-0'],
                    ['Müzik & Eğlence', 'images/hero/gimbal.png', 'yellow', 'bottom-[12%] left-0'],
                    ['Hediye Fikirleri', 'images/hero/smart-ring.png', 'pink', 'bottom-0 right-[10%]'],
                ] as [$orbitLabel, $orbitImg, $orbitTone, $orbitPos])
                <div class="catalog-hero__orbit-card catalog-hero__orbit-card--{{ $orbitTone }} {{ $orbitPos }}">
                    <img src="{{ asset($orbitImg) }}" alt="" loading="lazy" decoding="async">
                    <span>{{ $orbitLabel }}</span>
                </div>
                @endforeach
            </div>
            <div class="catalog-hero__metrics">
                <span class="catalog-hero__metric">
                    <strong>{{ $products->total() }}</strong>
                    <span>ürün listeleniyor</span>
                </span>
                <span class="catalog-hero__metric">
                    <strong>7/24</strong>
                    <span>online mağaza</span>
                </span>
                <span class="catalog-hero__metric">
                    <strong>%100</strong>
                    <span>orijinal ürün</span>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="site-container py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        @if(isset($categories))
        @php
            $activeCategorySlug = request('kategori') ?? (isset($activeCategory) ? $activeCategory->slug : null);
        @endphp
        <aside class="lg:w-64 flex-shrink-0" data-product-filters data-products-url="{{ route('products.index') }}">
            <div class="sidebar-panel sticky-below-header">
                <h3 class="sidebar-panel__title">Kategoriler</h3>
                <nav class="space-y-0.5" data-category-nav>
                    <button type="button"
                            data-category-filter
                            data-category-slug=""
                            @if(! $activeCategorySlug) aria-current="page" @endif
                            class="sidebar-cat {{ $activeCategorySlug ? 'sidebar-cat--idle' : 'sidebar-cat--active' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Tümü
                    </button>
                    @foreach($categories as $cat)
                    <button type="button"
                            data-category-filter
                            data-category-slug="{{ $cat->slug }}"
                            @if($activeCategorySlug === $cat->slug) aria-current="page" @endif
                            class="sidebar-cat {{ $activeCategorySlug === $cat->slug ? 'sidebar-cat--active' : 'sidebar-cat--idle' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        {{ $cat->name }}
                    </button>
                    @endforeach
                </nav>
                <div class="mt-5 border-t border-iw-border pt-4">
                    @php $advantageActive = request()->boolean('avantajli'); @endphp
                    <div class="mini-filter">
                        <span class="font-semibold text-iw-text">Avantajlı Ürünler</span>
                        <button type="button"
                                class="mini-toggle{{ $advantageActive ? ' is-active' : '' }}"
                                data-advantage-toggle
                                role="switch"
                                aria-checked="{{ $advantageActive ? 'true' : 'false' }}"
                                aria-label="Avantajlı ürünleri filtrele"></button>
                    </div>
                    <div class="mini-filter">
                        <span class="font-semibold text-iw-text">Kargo Bedava</span>
                        <span class="tag-pill tag-pill--green">Aktif</span>
                    </div>
                </div>
            </div>
        </aside>
        @endif

        <div class="flex-1 min-w-0">
            @include('partials.products-listing', ['products' => $products])
        </div>
    </div>
</div>

@endsection

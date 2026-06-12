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

<section class="page-hero page-hero--compact">
    <div class="relative max-w-[1200px] mx-auto px-6">
        <span class="eyebrow-badge mb-2">Ürün Kataloğu</span>
        @if($activeFilterLabel)
        <span class="active-filter-label">{{ $activeFilterLabel }}</span>
        <h1>{{ $activeFilterLabel }}</h1>
        <p class="max-w-lg">Seçili filtreye göre listelenen ürünler.</p>
        @elseif(isset($activeCategory))
        <h1>{{ $activeCategory->name }}</h1>
        @if($activeCategory->description)
        <p class="max-w-lg">{{ $activeCategory->description }}</p>
        @endif
        @else
        <h1>Tüm Ürünler</h1>
        <p class="max-w-lg">Akıllı cihazlardan eğlenceli oyuncaklara — kategori ve filtrelerle hızlıca bulun.</p>
        @endif
    </div>
</section>

<section class="filter-bar sticky-below-header z-30">
    <div class="filter-bar__inner">
        <div class="filter-row">
            <div class="filter-row__carousel scroll-row" data-scroll-row>
                <button type="button" class="carousel-btn-inline" data-scroll-row-prev aria-label="Önceki filtreler">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="filter-row__chips scroll-row__track" data-scroll-row-track>
                @foreach([
                    ['Flaş Ürünler', 'orange', 'flash', 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['Yüksek Puanlı Ürünler', 'yellow', 'high-rated', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ['Kargo Bedava', 'gray', 'free-shipping', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['Hızlı Teslimat', 'green', 'fast-delivery', 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                    ['Çok Satanlar', 'orange', 'bestseller', 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                    ['Akıllı Cihazlar', 'blue', 'smart-devices', 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ] as [$label, $tone, $slug, $icon])
                <a href="{{ route('products.index', ['filtre' => $slug]) }}" class="filter-chip filter-chip--{{ $tone }}{{ request('filtre') === $slug ? ' filter-chip--active' : '' }}">
                    <span class="filter-chip__icon">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                    </span>
                    {{ $label }}
                </a>
                @endforeach
                </div>
                <button type="button" class="carousel-btn-inline" data-scroll-row-next aria-label="Sonraki filtreler">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <form method="GET" action="{{ route('products.index') }}" class="filter-row__search">
                @if(request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
                @if(request('filtre'))<input type="hidden" name="filtre" value="{{ request('filtre') }}">@endif
                <input type="search" name="ara" value="{{ request('ara') }}" placeholder="Ürün ara…" class="input">
                <button type="submit" class="btn-primary flex-shrink-0" aria-label="Ara">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
        </div>
    </div>
</section>

<div class="max-w-[1200px] mx-auto px-6 py-10">
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

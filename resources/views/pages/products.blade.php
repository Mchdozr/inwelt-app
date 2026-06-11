@extends('layouts.app')

@section('title', isset($activeCategory) ? $activeCategory->name : 'Ürünler')
@section('description', isset($activeCategory) && $activeCategory->description
    ? $activeCategory->description
    : 'INWELT ürün kataloğu. Akıllı cihazlar, RC oyuncaklar, müzik setleri, zeka oyunları ve güvenlik ürünlerini inceleyin.')

@section('content')

<section class="page-hero py-16">
    <div class="relative max-w-[1200px] mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <span class="eyebrow-badge mb-3">Ürün Kataloğu</span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-iw-text tracking-tight">{{ isset($activeCategory) ? $activeCategory->name : 'Tüm Ürünler' }}</h1>
                @if(isset($activeCategory) && $activeCategory->description)
                <p class="mt-3 text-iw-text-muted max-w-lg">{{ $activeCategory->description }}</p>
                @else
                <p class="mt-3 text-iw-text-muted max-w-lg">Akıllı cihazlardan eğlenceli oyuncaklara tüm ürünlerimizi kategori bazında keşfedin.</p>
                @endif
            </div>
            <form method="GET" action="{{ route('products.index') }}" class="flex gap-2 w-full md:w-auto">
                @if(request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
                <input type="search" name="ara" value="{{ request('ara') }}" placeholder="Ürün ara…" class="input md:w-72">
                <button type="submit" class="btn-primary flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                </button>
            </form>
        </div>
    </div>
</section>

<section class="border-b border-iw-border bg-white">
    <div class="max-w-[1200px] mx-auto px-6 py-4">
        <div class="filter-row">
            @foreach([
                ['Flaş Ürünler', 'Yeni'],
                ['Yüksek Puanlı Ürünler', null],
                ['Kargo Bedava', null],
                ['Hızlı Teslimat', null],
                ['Çok Satanlar', 'Trend'],
                ['Akıllı Cihazlar', null],
                ['STEM Oyuncaklar', null],
                ['Seyahat Dostu', null],
            ] as [$label, $badge])
            <a href="{{ route('products.index', ['ara' => $label]) }}" class="quick-chip">
                <span>{{ $label }}</span>
                @if($badge)<strong>{{ $badge }}</strong>@endif
            </a>
            @endforeach
        </div>
    </div>
</section>

<div class="max-w-[1200px] mx-auto px-6 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        @if(isset($categories))
        @php
            $activeCategorySlug = request('kategori') ?? (isset($activeCategory) ? $activeCategory->slug : null);
        @endphp
        <aside class="lg:w-64 flex-shrink-0">
            <div class="iw-panel p-4 sticky top-20">
                <h3 class="text-xs font-bold tracking-widest uppercase text-iw-text-muted mb-3 px-2">Kategoriler</h3>
                <nav class="space-y-0.5">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition-colors {{ ! $activeCategorySlug ? 'bg-iw-accent/10 text-iw-accent font-medium border border-iw-border' : 'text-iw-text-muted hover:text-iw-text hover:bg-iw-accent/[0.06]' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Tümü
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['kategori' => $cat->slug]) }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition-colors {{ $activeCategorySlug === $cat->slug ? 'bg-iw-accent/10 text-iw-accent font-medium border border-iw-border' : 'text-iw-text-muted hover:text-iw-text hover:bg-iw-accent/[0.06]' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </nav>
                <div class="mt-5">
                    <div class="mini-filter">
                        <span class="font-semibold text-iw-text">Avantajlı Ürünler</span>
                        <span class="mini-toggle"></span>
                    </div>
                    <div class="mini-filter">
                        <span class="font-semibold text-iw-text">Kargo Bedava</span>
                        <span class="text-xs font-bold text-iw-accent">Aktif</span>
                    </div>
                    <div class="mini-filter">
                        <span class="font-semibold text-iw-text">Fiyat Aralığı</span>
                        <span class="text-iw-text-muted">Tümü</span>
                    </div>
                    <div class="mini-filter">
                        <span class="font-semibold text-iw-text">Ürün Puanı</span>
                        <span class="text-iw-amber">4.5+</span>
                    </div>
                </div>
            </div>
        </aside>
        @endif

        <div class="flex-1 min-w-0">
            @if($products->count())
            <div class="market-rail mb-6 p-4">
                <div class="flex flex-wrap items-center gap-2">
                    @foreach(['Yeni Gelenler', 'Popüler Seçimler', 'Akıllı Yaşam', 'Eğitici Oyuncak', 'Günlük Pratiklik'] as $tag)
                    <span class="rounded-full bg-iw-accent/[0.06] px-3 py-1.5 text-xs font-semibold text-iw-accent border border-iw-border">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            <div class="mb-5 flex items-center justify-between gap-3">
                <p class="text-sm text-iw-text-muted"><span class="font-semibold text-iw-text">{{ $products->total() }}</span> ürün listeleniyor</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="prod-card group flex flex-col no-underline">
                    <div class="prod-card-media">
                        <x-product-image :src="$product->cover_image" :alt="$product->name" class="prod-media" />
                    </div>
                    <div class="p-5 flex flex-col flex-1 gap-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-iw-accent bg-iw-accent/8 px-2.5 py-1 rounded-full border border-iw-border">{{ $product->category->name }}</span>
                            @if($product->badge)
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-iw-amber/10 text-iw-amber border border-iw-border">{{ $product->badge }}</span>
                            @endif
                        </div>
                        <h2 class="font-semibold text-iw-text leading-snug group-hover:text-iw-accent transition-colors line-clamp-2 min-h-[2.75rem]">{{ $product->name }}</h2>
                        @if($product->summary)
                        <p class="text-iw-text-muted text-sm line-clamp-2 flex-1">{{ $product->summary }}</p>
                        @endif
                        <div class="mt-2 pt-3 border-t border-iw-border flex items-center justify-between gap-2">
                            <span class="text-iw-accent text-sm font-semibold">Ürünü İncele</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-iw-accent/10 text-iw-accent group-hover:bg-iw-accent group-hover:text-white transition-colors">
                                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            @if($products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
            @endif

            @else
            <div class="iw-panel text-center py-20 text-iw-text-muted">
                <div class="icon-chip mx-auto mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p>Bu kategoride henüz ürün bulunmuyor.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

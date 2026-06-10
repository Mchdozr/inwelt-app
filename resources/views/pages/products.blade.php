@extends('layouts.app')

@section('title', isset($activeCategory) ? $activeCategory->name : 'Ürünler')
@section('description', isset($activeCategory) && $activeCategory->description
    ? $activeCategory->description
    : 'INWELT ürün kataloğu. Akıllı sistemler, endüstriyel çözümler ve aksesuarlarımızı inceleyin.')

@section('content')

{{-- PAGE HEADER --}}
<section class="py-16 bg-gradient-to-b from-iw-card/50 to-transparent border-b border-iw-border">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="text-xs font-bold tracking-widest uppercase text-iw-accent mb-2">Ürün Kataloğu</div>
                <h1 class="text-3xl font-extrabold text-white">{{ isset($activeCategory) ? $activeCategory->name : 'Tüm Ürünler' }}</h1>
                @if(isset($activeCategory) && $activeCategory->description)
                <p class="mt-2 text-iw-text-muted max-w-lg">{{ $activeCategory->description }}</p>
                @endif
            </div>
            {{-- Search --}}
            <form method="GET" action="{{ route('products.index') }}" class="flex gap-2 w-full md:w-auto">
                @if(request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
                <input type="search" name="ara" value="{{ request('ara') }}" placeholder="Ürün ara…" class="input md:w-64">
                <button type="submit" class="btn-primary flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                </button>
            </form>
        </div>
    </div>
</section>

<div class="max-w-[1200px] mx-auto px-6 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- SIDEBAR KATEGORİLER --}}
        @if(isset($categories))
        <aside class="lg:w-60 flex-shrink-0">
            <div class="bg-iw-card border border-iw-border rounded-2xl p-4 sticky top-20">
                <h3 class="text-xs font-bold tracking-widest uppercase text-iw-text-muted mb-3 px-2">Kategoriler</h3>
                <nav class="space-y-0.5">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ !request('kategori') ? 'bg-iw-accent/10 text-iw-accent font-medium' : 'text-iw-text-muted hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Tümü
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['kategori' => $cat->slug]) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('kategori') === $cat->slug ? 'bg-iw-accent/10 text-iw-accent font-medium' : 'text-iw-text-muted hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </nav>
            </div>
        </aside>
        @endif

        {{-- ÜRÜN GRID --}}
        <div class="flex-1 min-w-0">
            @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="iw-card group flex flex-col no-underline">
                    <div class="aspect-[4/3] bg-iw-card-hover overflow-hidden">
                        @if($product->cover_image)
                        <img src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-iw-border" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        @if($product->badge)
                        <span class="self-start mb-2 text-xs font-bold px-2.5 py-1 rounded-full bg-iw-accent/10 text-iw-accent border border-iw-border">{{ $product->badge }}</span>
                        @endif
                        <div class="text-xs text-iw-text-muted mb-1">{{ $product->category->name }}</div>
                        <h2 class="font-semibold text-iw-text group-hover:text-iw-accent transition-colors">{{ $product->name }}</h2>
                        @if($product->summary)
                        <p class="text-iw-text-muted text-sm mt-2 line-clamp-2 flex-1">{{ $product->summary }}</p>
                        @endif
                        <div class="flex items-center gap-1 mt-4 text-iw-accent text-sm font-medium">
                            <span>Detay</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
            @endif

            @else
            <div class="text-center py-20 text-iw-text-muted">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p>Bu kategoride henüz ürün bulunmuyor.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

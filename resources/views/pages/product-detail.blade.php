@extends('layouts.app')

@section('title', $product->seo_title ?: $product->name)
@section('description', $product->seo_description ?: ($product->summary ?: $product->name))
@section('image', $product->cover_image ?? '')
@section('og_type', 'product')

@push('head')
@php
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => $product->seo_description ?: $product->summary,
        'url' => route('products.show', $product->slug),
        'category' => $product->category->name,
        'brand' => ['@type' => 'Brand', 'name' => 'INWELT'],
    ];
    if ($product->cover_image) {
        $productSchema['image'] = url(Storage::url($product->cover_image));
    }
@endphp
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

<article class="max-w-[1200px] mx-auto px-6 py-10">

    {{-- BREADCRUMB --}}
    <nav class="flex items-center gap-2 text-sm text-iw-text-muted mb-8">
        <a href="{{ route('home') }}" class="hover:text-iw-accent transition-colors">Ana Sayfa</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.index') }}" class="hover:text-iw-accent transition-colors">Ürünler</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-iw-accent transition-colors">{{ $product->category->name }}</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-iw-text">{{ $product->name }}</span>
    </nav>

    {{-- HERO BÖLÜMÜ --}}
    <div class="grid lg:grid-cols-2 gap-10 mb-16">

        {{-- Görsel / Galeri --}}
        <div>
            <div id="mainImage" class="aspect-[4/3] bg-iw-card border border-iw-border rounded-2xl overflow-hidden">
                @if($product->cover_image)
                <img id="mainImg" src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-20 h-20 text-iw-border" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                </div>
                @endif
            </div>
            @if($product->images->count())
            <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
                @if($product->cover_image)
                <button onclick="setImg('{{ Storage::url($product->cover_image) }}')" class="flex-shrink-0 w-16 h-16 rounded-xl border-2 border-iw-accent overflow-hidden focus:outline-none">
                    <img src="{{ Storage::url($product->cover_image) }}" class="w-full h-full object-cover" alt="">
                </button>
                @endif
                @foreach($product->images->sortBy('sort') as $img)
                <button onclick="setImg('{{ Storage::url($img->path) }}')" class="flex-shrink-0 w-16 h-16 rounded-xl border-2 border-iw-border hover:border-iw-accent overflow-hidden transition-colors focus:outline-none">
                    <img src="{{ Storage::url($img->path) }}" class="w-full h-full object-cover" alt="{{ $img->alt }}">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Bilgi --}}
        <div>
            @if($product->badge)
            <span class="inline-block mb-3 text-xs font-bold px-3 py-1 rounded-full bg-iw-accent/10 text-iw-accent border border-iw-border">{{ $product->badge }}</span>
            @endif
            <div class="text-sm text-iw-text-muted mb-2">
                <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-iw-accent transition-colors">{{ $product->category->name }}</a>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ $product->name }}</h1>

            @if($product->summary)
            <p class="mt-4 text-iw-text-muted leading-relaxed">{{ $product->summary }}</p>
            @endif

            {{-- Teknik özellikler özeti --}}
            @if($product->specs->count())
            <div class="mt-6 bg-iw-card border border-iw-border rounded-xl divide-y divide-iw-border">
                @foreach($product->specs->sortBy('sort')->take(6) as $spec)
                <div class="flex items-center justify-between px-4 py-2.5 text-sm">
                    <span class="text-iw-text-muted">{{ $spec->label }}</span>
                    <span class="font-medium text-iw-text">{{ $spec->value }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('contact') }}" class="btn-primary px-6 py-3">
                    Teklif Al
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </a>
                @if($product->pdf_path)
                <a href="{{ Storage::url($product->pdf_path) }}" target="_blank" class="btn-outline px-6 py-3">
                    Katalog İndir
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- DETAY TABS --}}
    <div x-data="{ tab: 'desc' }" class="mb-16">
        {{-- Tab buttons --}}
        <div class="flex gap-1 border-b border-iw-border mb-6 overflow-x-auto">
            @if($product->description)
            <button @click="tab='desc'" :class="tab==='desc' ? 'text-iw-accent border-b-2 border-iw-accent' : 'text-iw-text-muted hover:text-white'" class="px-5 py-3 text-sm font-semibold whitespace-nowrap transition-colors pb-[11px]">Ürün Açıklaması</button>
            @endif
            @if($product->specs->count() > 6)
            <button @click="tab='specs'" :class="tab==='specs' ? 'text-iw-accent border-b-2 border-iw-accent' : 'text-iw-text-muted hover:text-white'" class="px-5 py-3 text-sm font-semibold whitespace-nowrap transition-colors pb-[11px]">Teknik Özellikler</button>
            @endif
            @if($product->useCases->count())
            <button @click="tab='usecases'" :class="tab==='usecases' ? 'text-iw-accent border-b-2 border-iw-accent' : 'text-iw-text-muted hover:text-white'" class="px-5 py-3 text-sm font-semibold whitespace-nowrap transition-colors pb-[11px]">Kullanım Alanları</button>
            @endif
        </div>

        @if($product->description)
        <div x-show="tab==='desc'" class="prose prose-invert max-w-none text-iw-text-muted leading-relaxed">
            {!! $product->description !!}
        </div>
        @endif

        @if($product->specs->count() > 6)
        <div x-show="tab==='specs'" x-cloak>
            <div class="bg-iw-card border border-iw-border rounded-2xl overflow-hidden">
                <div class="divide-y divide-iw-border">
                    @foreach($product->specs->sortBy('sort') as $spec)
                    <div class="flex items-center justify-between px-6 py-3 text-sm hover:bg-iw-card-hover transition-colors">
                        <span class="text-iw-text-muted">{{ $spec->label }}</span>
                        <span class="font-medium text-iw-text">{{ $spec->value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($product->useCases->count())
        <div x-show="tab==='usecases'" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($product->useCases->sortBy('sort') as $uc)
                <div class="bg-iw-card border border-iw-border rounded-xl p-5">
                    <div class="w-10 h-10 rounded-lg bg-iw-accent/10 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-iw-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h4 class="font-semibold text-iw-text">{{ $uc->title }}</h4>
                    @if($uc->text)<p class="text-iw-text-muted text-sm mt-1">{{ $uc->text }}</p>@endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- İLGİLİ ÜRÜNLER --}}
    @if($related->count())
    <section>
        <h2 class="text-xl font-bold text-white mb-6">Benzer Ürünler</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($related as $rel)
            <a href="{{ route('products.show', $rel->slug) }}" class="iw-card group flex flex-col no-underline">
                <div class="aspect-[4/3] bg-iw-card-hover overflow-hidden">
                    @if($rel->cover_image)
                    <img src="{{ Storage::url($rel->cover_image) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-iw-border" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16"/></svg>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-iw-text group-hover:text-iw-accent transition-colors line-clamp-2">{{ $rel->name }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

</article>

@push('head')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush

@push('scripts')
<script>
function setImg(src) {
    document.getElementById('mainImg')?.setAttribute('src', src);
}
</script>
@endpush

@endsection

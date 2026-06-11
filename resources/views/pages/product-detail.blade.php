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

    <nav class="flex items-center gap-2 text-sm text-iw-text-muted mb-8 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-iw-accent transition-colors">Ana Sayfa</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.index') }}" class="hover:text-iw-accent transition-colors">Ürünler</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-iw-accent transition-colors">{{ $product->category->name }}</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-iw-text">{{ $product->name }}</span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-10 mb-16">
        <div>
            <div id="mainImage" class="prod-detail-media">
                @if($product->cover_image)
                <img id="mainImg" src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->name }}" class="prod-media">
                @else
                <x-product-image :src="null" :alt="$product->name" :lazy="false" />
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

        <div>
            @if($product->badge)
            <span class="inline-block mb-3 text-xs font-bold px-3 py-1 rounded-full bg-iw-accent/10 text-iw-accent border border-iw-border">{{ $product->badge }}</span>
            @endif
            <div class="text-sm text-iw-amber mb-2 font-medium">
                <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-iw-accent transition-colors">{{ $product->category->name }}</a>
            </div>
            <h1 class="text-3xl font-extrabold text-iw-text tracking-tight">{{ $product->name }}</h1>

            @if($product->summary)
            <p class="mt-4 text-iw-text-muted leading-relaxed">{{ $product->summary }}</p>
            @endif

            @if($product->specs->count())
            <div class="mt-6 iw-panel divide-y divide-iw-border overflow-hidden">
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
                    Sipariş & Bilgi İçin İletişim
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </a>
                @if($product->seller_url)
                <a href="{{ $product->seller_url }}" target="_blank" rel="noopener noreferrer" class="btn-outline px-6 py-3">
                    Kacmasa'da İncele
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                @endif
                @if($product->pdf_path)
                <a href="{{ Storage::url($product->pdf_path) }}" target="_blank" class="btn-outline px-6 py-3">
                    Katalog İndir
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div x-data="{ tab: 'desc' }" class="mb-16">
        <div class="flex gap-1 border-b border-iw-border mb-6 overflow-x-auto">
            @if($product->description)
            <button @click="tab='desc'" :class="tab==='desc' ? 'text-iw-accent border-b-2 border-iw-accent' : 'text-iw-text-muted hover:text-iw-text'" class="px-5 py-3 text-sm font-semibold whitespace-nowrap transition-colors pb-[11px]">Ürün Açıklaması</button>
            @endif
            @if($product->specs->count() > 6)
            <button @click="tab='specs'" :class="tab==='specs' ? 'text-iw-accent border-b-2 border-iw-accent' : 'text-iw-text-muted hover:text-iw-text'" class="px-5 py-3 text-sm font-semibold whitespace-nowrap transition-colors pb-[11px]">Teknik Özellikler</button>
            @endif
            @if($product->useCases->count())
            <button @click="tab='usecases'" :class="tab==='usecases' ? 'text-iw-accent border-b-2 border-iw-accent' : 'text-iw-text-muted hover:text-iw-text'" class="px-5 py-3 text-sm font-semibold whitespace-nowrap transition-colors pb-[11px]">Kullanım Alanları</button>
            @endif
        </div>

        @if($product->description)
        <div x-show="tab==='desc'" class="prose max-w-none text-iw-text-muted leading-relaxed">
            {!! $product->description !!}
        </div>
        @endif

        @if($product->specs->count() > 6)
        <div x-show="tab==='specs'" x-cloak>
            <div class="iw-panel overflow-hidden">
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
                <div class="iw-panel p-5">
                    <div class="icon-chip mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h4 class="font-semibold text-iw-text">{{ $uc->title }}</h4>
                    @if($uc->text)<p class="text-iw-text-muted text-sm mt-1">{{ $uc->text }}</p>@endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @if($related->count())
    <section>
        <h2 class="text-xl font-bold text-iw-text mb-6">Benzer Ürünler</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($related as $rel)
            <a href="{{ route('products.show', $rel->slug) }}" class="prod-card group flex flex-col no-underline">
                <div class="prod-card-media">
                    <x-product-image :src="$rel->cover_image" :alt="$rel->name" class="prod-media" />
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

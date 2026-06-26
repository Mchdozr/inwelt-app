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

<article class="site-container py-10">
@php
    $galleryImages = collect();

    if ($product->cover_image) {
        $galleryImages->push([
            'src' => Storage::url($product->cover_image),
            'alt' => $product->name,
        ]);
    }

    foreach ($product->images->sortBy('sort') as $img) {
        $galleryImages->push([
            'src' => Storage::url($img->path),
            'alt' => $img->alt ?: $product->name,
        ]);
    }
@endphp

    <nav class="breadcrumb reveal" aria-label="Konum">
        <a href="{{ route('home') }}">Ana Sayfa</a>
        <svg class="breadcrumb__sep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.index') }}">Ürünler</a>
        <svg class="breadcrumb__sep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a>
        <svg class="breadcrumb__sep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="breadcrumb__current">{{ $product->name }}</span>
    </nav>

    <div class="product-detail-grid reveal">
        <div>
            <div x-data="productGallery(@js($galleryImages->values()))">
            <button type="button" id="mainImage" class="prod-detail-media group w-full cursor-zoom-in focus:outline-none focus:ring-2 focus:ring-iw-accent/30" @click="openLightbox(activeIndex)" aria-label="Ürün görselini büyüt">
                @if($galleryImages->count())
                <img id="mainImg" :src="activeImage.src" :alt="activeImage.alt" src="{{ $galleryImages->first()['src'] }}" alt="{{ $product->name }}" class="prod-media">
                <span class="gallery-zoom-hint">Büyüt</span>
                @else
                <x-product-image :src="null" :alt="$product->name" :lazy="false" />
                @endif
            </button>
            @if($galleryImages->count() > 1)
            <div class="gallery-thumbs mt-3">
                <button type="button" @click="scrollThumbs(-1)" class="gallery-thumbs__nav" aria-label="Önceki görseller">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div x-ref="thumbTrack" class="gallery-thumbs__track no-scrollbar">
                    @foreach($galleryImages as $index => $img)
                    <button type="button" @click="setActive({{ $index }})" :class="activeIndex === {{ $index }} ? 'is-active' : ''" class="gallery-thumb focus:outline-none">
                        <img src="{{ $img['src'] }}" class="w-full h-full object-contain p-1.5" alt="{{ $img['alt'] }}">
                    </button>
                    @endforeach
                </div>
                <button type="button" @click="scrollThumbs(1)" class="gallery-thumbs__nav" aria-label="Sonraki görseller">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            @endif
            <div
                x-show="modalOpen"
                x-cloak
                x-transition.opacity
                class="gallery-lightbox pointer-events-none fixed inset-0 z-[80]"
                @keydown.escape.window="closeLightbox()"
            >
                <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-[2px]" aria-hidden="true"></div>
                <div class="gallery-lightbox__panel pointer-events-auto fixed left-1/2 top-1/2 z-[81] flex w-[calc(100%-2rem)] max-w-6xl -translate-x-1/2 -translate-y-1/2 flex-col overflow-hidden rounded-2xl bg-white shadow-[0_30px_80px_rgba(15,23,42,0.35)] md:max-h-[min(88vh,720px)] md:flex-row">
                    <div class="relative flex min-h-[240px] flex-1 items-center justify-center bg-white p-6 md:min-h-0 md:p-10">
                        <button type="button" @click="prev()" class="absolute left-4 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-iw-border bg-white/90 text-iw-text shadow hover:text-iw-accent" aria-label="Önceki görsel">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <img :src="activeImage.src" :alt="activeImage.alt" class="max-h-[78vh] max-w-full object-contain">
                        <button type="button" @click="next()" class="absolute right-4 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-iw-border bg-white/90 text-iw-text shadow hover:text-iw-accent" aria-label="Sonraki görsel">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    <aside class="w-full border-t border-iw-border bg-white p-5 md:w-72 md:border-l md:border-t-0">
                        <div class="mb-4 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-iw-accent">Ürün Galerisi</p>
                                <h2 class="mt-1 text-sm font-semibold leading-snug text-iw-text">{{ $product->name }}</h2>
                            </div>
                            <button type="button" @click="closeLightbox()" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full border border-iw-border text-iw-text-muted hover:text-iw-text" aria-label="Kapat">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="grid max-h-[52vh] grid-cols-4 gap-2 overflow-y-auto pr-1 md:grid-cols-3">
                            @foreach($galleryImages as $index => $img)
                            <button type="button" @click="setActive({{ $index }})" :class="activeIndex === {{ $index }} ? 'border-iw-accent ring-2 ring-iw-accent/20' : 'border-iw-border'" class="aspect-square rounded-lg border-2 bg-white p-1 transition-colors hover:border-iw-accent">
                                <img src="{{ $img['src'] }}" class="h-full w-full object-contain" alt="{{ $img['alt'] }}">
                            </button>
                            @endforeach
                        </div>
                        <p class="mt-4 text-xs text-iw-text-muted"><span x-text="activeIndex + 1"></span> / {{ $galleryImages->count() }} görsel</p>
                    </aside>
                </div>
            </div>
            </div>
        </div>

        <div class="product-info-panel">
            @if($product->badge)
            <span class="product-badge">{{ $product->badge }}</span>
            @endif
            <a href="{{ route('products.category', $product->category->slug) }}" class="product-category-link">{{ $product->category->name }}</a>
            <h1 class="product-title">{{ $product->name }}</h1>

            @if($product->summary)
            <p class="product-summary">{{ $product->summary }}</p>
            @endif

            @if($product->specs->count())
            <div class="spec-table">
                @foreach($product->specs->sortBy('sort')->take(6) as $spec)
                <div class="spec-table__row">
                    <span class="text-iw-text-muted">{{ $spec->label }}</span>
                    <span class="font-semibold text-iw-text">{{ $spec->value }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <div class="mt-8 flex flex-col gap-4">
                @include('partials.marketplace-buttons', ['product' => $product])
                <a href="{{ route('contact') }}" class="btn-primary px-6 py-3 self-start">
                    Sipariş & Bilgi İçin İletişim
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </a>
                @if($product->pdf_path)
                <div class="flex flex-wrap gap-3">
                <a href="{{ Storage::url($product->pdf_path) }}" target="_blank" class="btn-outline px-6 py-3">
                    Katalog İndir
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="product-trust-strip reveal" style="--reveal-delay: 0.1s">
        @foreach(['Hızlı Teslimat', 'Kargo Avantajı', 'Güvenli Ödeme', 'Orijinal Ürün', 'Satıcı Linki', '7/24 Destek'] as $title)
        <span class="product-trust-chip">{{ $title }}</span>
        @endforeach
    </div>

    <div x-data="{ tab: 'desc' }" class="mb-16 reveal" style="--reveal-delay: 0.14s">
        <div class="tab-nav">
            @if($product->description)
            <button type="button" @click="tab='desc'" :class="tab==='desc' ? 'is-active' : ''" class="tab-nav__btn">Ürün Açıklaması</button>
            @endif
            @if($product->specs->count() > 6)
            <button type="button" @click="tab='specs'" :class="tab==='specs' ? 'is-active' : ''" class="tab-nav__btn">Teknik Özellikler</button>
            @endif
            @if($product->useCases->count())
            <button type="button" @click="tab='usecases'" :class="tab==='usecases' ? 'is-active' : ''" class="tab-nav__btn">Kullanım Alanları</button>
            @endif
        </div>

        @if($product->description)
        <div x-show="tab==='desc'" class="prose-iw">
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
                <div class="value-card">
                    <div class="value-card__icon">
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
    <section class="border-t border-iw-border pt-12 reveal">
        <div class="section-head mb-8">
            <h2>Bu kategoriden öneriler</h2>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4" data-reveal-stagger=".prod-card">
            @foreach($related as $rel)
            <x-product-card :product="$rel" compact />
            @endforeach
        </div>
    </section>
    @endif

</article>

@if($product->seller_url)
<div class="product-sticky-cta lg:hidden" id="productStickyCta">
    <div class="product-sticky-cta__inner site-container">
        <a
            href="{{ \App\Support\ProductMarketplace::kacmasaUrl($product) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn-primary product-sticky-cta__btn"
            data-track-marketplace="kacmasa"
            data-product-slug="{{ $product->slug }}"
        >
            Kacmasa'da Satın Al
        </a>
    </div>
</div>
@endif

@push('scripts')
<script>
document.getElementById('productStickyCta')?.classList.add('is-visible');
</script>
@endpush

@push('head')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush

@push('scripts')
<script>
function productGallery(images) {
    return {
        images,
        activeIndex: 0,
        modalOpen: false,
        get activeImage() {
            return this.images[this.activeIndex] ?? { src: '', alt: '' };
        },
        setActive(index) {
            this.activeIndex = index;
            this.$nextTick(() => this.scrollThumbIntoView(index));
        },
        scrollThumbs(direction) {
            const track = this.$refs.thumbTrack;

            if (! track) {
                return;
            }

            track.scrollBy({ left: direction * Math.max(track.clientWidth * 0.75, 200), behavior: 'smooth' });
        },
        scrollThumbIntoView(index) {
            const track = this.$refs.thumbTrack;
            const thumb = track?.children[index];

            thumb?.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
        },
        openLightbox(index) {
            if (! this.images.length) {
                return;
            }

            this.activeIndex = index;
            this.modalOpen = true;
        },
        closeLightbox() {
            this.modalOpen = false;
        },
        next() {
            this.activeIndex = (this.activeIndex + 1) % this.images.length;
        },
        prev() {
            this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
        },
    };
}
</script>
@endpush

@endsection

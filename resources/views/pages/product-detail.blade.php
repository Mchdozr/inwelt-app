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

    <div
        class="product-detail-grid reveal"
        x-data="productGallery(@js($galleryImages->values()))"
        @keydown.window="handleKeydown($event)"
    >
        <div>
            <button type="button" id="mainImage" class="prod-detail-media group w-full cursor-zoom-in focus:outline-none focus-visible:ring-2 focus-visible:ring-iw-brand/40" @click="openLightbox(activeIndex)" aria-label="Ürün görselini büyüt">
                @if($galleryImages->count())
                <img id="mainImg" :src="activeImage.src" :alt="activeImage.alt" src="{{ $galleryImages->first()['src'] }}" alt="{{ $product->name }}" class="prod-media">
                <span class="gallery-zoom-hint">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    Yakınlaştır
                </span>
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

        <template x-teleport="body">
        <div
            x-show="modalOpen"
            x-cloak
            class="gallery-lightbox"
            role="dialog"
            aria-modal="true"
            :aria-label="'Ürün görseli ' + (activeIndex + 1) + ' / ' + images.length"
        >
            <div
                class="gallery-lightbox__backdrop"
                x-show="modalOpen"
                x-transition:enter="transition-opacity duration-300 ease-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-200 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                aria-hidden="true"
            ></div>

            <div
                class="gallery-lightbox__toolbar"
                x-show="modalOpen"
                x-transition:enter="transition ease-out duration-300 delay-75"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                <div class="gallery-lightbox__counter">
                    <span x-text="activeIndex + 1"></span>
                    <span class="gallery-lightbox__counter-sep">/</span>
                    <span x-text="images.length"></span>
                </div>
                <button type="button" @click="closeLightbox()" class="gallery-lightbox__close" aria-label="Kapat">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($galleryImages->count() > 1)
            <button type="button" @click="prev()" class="gallery-lightbox__nav gallery-lightbox__nav--prev" aria-label="Önceki görsel">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" @click="next()" class="gallery-lightbox__nav gallery-lightbox__nav--next" aria-label="Sonraki görsel">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            @endif

            <div class="gallery-lightbox__stage" x-show="modalOpen">
                <img
                    :key="activeIndex"
                    :src="activeImage.src"
                    :alt="activeImage.alt"
                    class="gallery-lightbox__image"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-[0.96]"
                    x-transition:enter-end="opacity-100 scale-100"
                >
            </div>

            @if($galleryImages->count() > 1)
            <div
                class="gallery-lightbox__filmstrip"
                x-show="modalOpen"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 translate-y-3"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                <div x-ref="lightboxThumbTrack" class="gallery-lightbox__filmstrip-track no-scrollbar">
                    @foreach($galleryImages as $index => $img)
                    <button
                        type="button"
                        @click="setActive({{ $index }})"
                        :class="activeIndex === {{ $index }} ? 'is-active' : ''"
                        class="gallery-lightbox__film-thumb focus:outline-none focus-visible:ring-2 focus-visible:ring-white/50"
                    >
                        <img src="{{ $img['src'] }}" alt="{{ $img['alt'] }}" loading="lazy" decoding="async">
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        </template>
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
            this.$nextTick(() => {
                this.scrollThumbIntoView(index);
                this.scrollLightboxThumbIntoView(index);
            });
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
        scrollLightboxThumbIntoView(index) {
            const track = this.$refs.lightboxThumbTrack;
            const thumb = track?.children[index];

            thumb?.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        },
        handleKeydown(event) {
            if (! this.modalOpen) {
                return;
            }

            if (event.key === 'Escape') {
                this.closeLightbox();
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                this.next();
            }

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                this.prev();
            }
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

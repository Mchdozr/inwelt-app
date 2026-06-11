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
            <div x-data="productGallery(@js($galleryImages->values()))" @keydown.escape.window="closeLightbox()">
            <button type="button" id="mainImage" class="prod-detail-media group w-full cursor-zoom-in focus:outline-none focus:ring-2 focus:ring-iw-accent/30" @click="openLightbox(activeIndex)" aria-label="Ürün görselini büyüt">
                @if($galleryImages->count())
                <img id="mainImg" :src="activeImage.src" :alt="activeImage.alt" src="{{ $galleryImages->first()['src'] }}" alt="{{ $product->name }}" class="prod-media">
                <span class="absolute right-4 bottom-4 z-20 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-iw-text shadow-[0_10px_24px_rgba(15,23,42,0.14)] border border-iw-border">
                    Büyüt
                </span>
                @else
                <x-product-image :src="null" :alt="$product->name" :lazy="false" />
                @endif
            </button>
            @if($galleryImages->count() > 1)
            <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
                @foreach($galleryImages as $index => $img)
                <button type="button" @click="setActive({{ $index }})" :class="activeIndex === {{ $index }} ? 'border-iw-accent ring-2 ring-iw-accent/15' : 'border-iw-border'" class="flex-shrink-0 w-16 h-16 rounded-xl border-2 bg-white overflow-hidden hover:border-iw-accent transition-colors focus:outline-none">
                    <img src="{{ $img['src'] }}" class="w-full h-full object-contain p-1.5" alt="{{ $img['alt'] }}">
                </button>
                @endforeach
            </div>
            @endif
            <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[80] bg-slate-950/65 backdrop-blur-sm p-4 md:p-8" @click.self="closeLightbox()">
                <div class="mx-auto flex h-full max-w-6xl flex-col overflow-hidden rounded-2xl bg-white shadow-[0_30px_80px_rgba(15,23,42,0.35)] md:flex-row">
                    <div class="relative flex min-h-[320px] flex-1 items-center justify-center bg-white p-6 md:p-10">
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

    <section class="mb-16">
        <div class="market-rail p-4">
            <div class="filter-row">
                @foreach([
                    ['Hızlı Teslimat', 'Stoktan gönderim'],
                    ['Kargo Avantajı', 'Sipariş desteği'],
                    ['Güvenli Ödeme', 'Taksitli alışveriş'],
                    ['Orijinal Görseller', 'Galeri detaylı'],
                    ['Satıcı Linki', 'Kacmasa yönlendirme'],
                    ['Destek', 'İletişimden bilgi al'],
                ] as [$title, $desc])
                <div class="quick-chip">
                    <span>{{ $title }}</span>
                    <small class="text-xs font-medium text-iw-text-muted">{{ $desc }}</small>
                </div>
                @endforeach
            </div>
        </div>
    </section>

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
        },
        openLightbox(index) {
            if (! this.images.length) {
                return;
            }

            this.activeIndex = index;
            this.modalOpen = true;
            document.documentElement.classList.add('overflow-hidden');
        },
        closeLightbox() {
            this.modalOpen = false;
            document.documentElement.classList.remove('overflow-hidden');
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

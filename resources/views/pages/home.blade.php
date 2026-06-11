@extends('layouts.app')

@section('title', 'Ana Sayfa')
@section('description', 'INWELT — akıllı cihazlar, RC oyuncaklar, dijital müzik setleri, zeka oyunları ve güvenlik ürünleri. Hayatı kolaylaştıran teknolojiyi keşfedin.')

@section('content')

{{-- HERO --}}
<section class="border-b border-iw-border bg-iw-panel">
    <div class="max-w-[1200px] mx-auto px-6 py-16 md:py-24 lg:py-28 grid lg:grid-cols-2 gap-12 lg:gap-16 items-center hero-editorial">
        <div>
            <p class="text-xs font-semibold tracking-[0.14em] uppercase text-iw-text-muted mb-6">Teknoloji &amp; yaşam</p>
            <h1>Hayatı kolaylaştıran akıllı ürünler</h1>
            <p class="mt-6 text-iw-text-muted text-base md:text-lg leading-relaxed max-w-md">
                Akıllı cihazlardan RC oyuncaklara, müzik setlerinden zeka oyunlarına — seçilmiş ürünler, sade bir alışveriş deneyimi.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-4">
                <a href="{{ route('products.index') }}" class="btn-primary">Ürünleri keşfet</a>
                <a href="{{ route('about') }}" class="btn-ghost">Hakkımızda →</a>
            </div>
            <div class="trust-inline mt-12 pt-8 border-t border-iw-border">
                <span class="trust-pill trust-pill--green">Hızlı kargo</span>
                <span class="trust-pill trust-pill--blue">Güvenli ödeme</span>
                <span class="trust-pill trust-pill--orange">Kolay iletişim</span>
            </div>
        </div>

        @php $heroShowcaseMode = 'composite'; @endphp
        @if($heroShowcaseMode === 'composite')
        <div class="hero-float hero-float--composite" aria-hidden="true">
            <img src="{{ asset('images/hero/hero-composite.png') }}" alt="INWELT ürün vitrini" width="1024" height="1024" loading="eager" decoding="async">
        </div>
        @else
        @include('partials.hero-float-grid')
        @endif
    </div>
</section>

{{-- HIZLI KEŞİF --}}
<section class="border-b border-iw-border bg-iw-deep">
    <div class="max-w-[1200px] mx-auto px-6 py-8">
        <div class="flex items-center justify-between gap-4 mb-4">
            <h2 class="text-sm font-semibold text-iw-text">Keşfet</h2>
            <a href="{{ route('products.index') }}" class="btn-ghost text-xs">Tümü →</a>
        </div>
        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
            @foreach([
                ['Fırsat Ürünleri', 'orange', 'M13 10V3L4 14h7v7l9-11h-7z'],
                ['Çok Satanlar', 'yellow', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                ['Kargo Bedava', 'gray', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['Hızlı Teslimat', 'green', 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                ['Akıllı Cihazlar', 'blue', 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ['STEM Oyuncaklar', 'yellow', 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z'],
            ] as [$title, $tone, $icon])
            <a href="{{ route('products.index', ['ara' => $title]) }}" class="filter-chip filter-chip--{{ $tone }}">
                <span class="filter-chip__icon">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                </span>
                {{ $title }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- KATEGORİLER --}}
@if($categories->count())
<section class="py-16 md:py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-head">
            <p class="label">Kategoriler</p>
            <h2>Ürün grupları</h2>
            <p>İlgi alanınıza göre filtreleyin.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('products.category', $cat->slug) }}" class="iw-card group p-5 flex items-start justify-between gap-4 no-underline">
                <div class="min-w-0">
                    <h3 class="font-medium text-iw-text group-hover:text-iw-text-muted transition-colors">{{ $cat->name }}</h3>
                    @if($cat->description)
                    <p class="text-iw-text-muted text-sm mt-1 line-clamp-2">{{ $cat->description }}</p>
                    @endif
                </div>
                <svg class="w-4 h-4 shrink-0 text-iw-text-muted mt-1 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ÖNE ÇIKAN ÜRÜNLER --}}
@if($featured->count())
<section class="py-16 md:py-20 section-muted border-y border-iw-border">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-head">
            <p class="label">Öne çıkan</p>
            <h2>Popüler ürünler</h2>
        </div>
        <div class="carousel-wrap" id="featuredCarousel">
            <div class="carousel-track" id="featuredTrack">
            @foreach($featured as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="prod-card group flex min-w-[260px] sm:min-w-[300px] lg:min-w-[320px] flex-col no-underline">
                <div class="prod-card-media">
                    <x-product-image :src="$product->cover_image" :alt="$product->name" class="prod-media" />
                </div>
                <div class="p-5 flex flex-col flex-1">
                    @if($product->badge)
                    <span class="badge-deal self-start mb-2">{{ $product->badge }}</span>
                    @endif
                    <h3 class="font-medium text-iw-text leading-snug">{{ $product->name }}</h3>
                    @if($product->summary)
                    <p class="text-iw-text-muted text-sm mt-2 line-clamp-2 flex-1">{{ $product->summary }}</p>
                    @endif
                    <span class="mt-4 text-xs font-semibold text-iw-brand group-hover:opacity-80 transition-opacity">İncele →</span>
                </div>
            </a>
            @endforeach
            </div>
            <button type="button" class="carousel-btn carousel-btn-prev" id="featuredPrev" aria-label="Önceki">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" class="carousel-btn carousel-btn-next" id="featuredNext" aria-label="Sonraki">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
        <div class="mt-10">
            <a href="{{ route('products.index') }}" class="btn-outline">Tüm ürünler</a>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-16 md:py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="cta-panel p-8 md:p-12 lg:p-14">
            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <h2 class="text-2xl md:text-3xl font-semibold tracking-tight">Aradığınız ürünü birlikte bulalım</h2>
                    <p class="mt-4 opacity-70 max-w-md text-sm md:text-base">Stok, özellik veya sipariş hakkında sorularınız için bize ulaşın.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" class="cta-btn-primary">İletişime geç</a>
                        <a href="{{ route('products.index') }}" class="cta-btn-secondary">Ürünleri incele</a>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-3 text-sm">
                    @foreach([
                        ['Hızlı kargo', 'Aynı gün kargoya teslim'],
                        ['Güvenli ödeme', 'Taksit ve güvenli altyapı'],
                        ['Geniş yelpaze', 'Akıllı cihazdan oyuncağa'],
                        ['Kolay iletişim', 'Hızlı yanıt'],
                    ] as [$title, $desc])
                    <div class="cta-benefit">
                        <div>
                            <div class="font-medium">{{ $title }}</div>
                            <div class="opacity-60 mt-0.5 text-xs">{{ $desc }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('featuredTrack');
    const prev = document.getElementById('featuredPrev');
    const next = document.getElementById('featuredNext');
    if (!track || !prev || !next) return;

    function scrollStep() {
        const card = track.querySelector('.prod-card');
        return card ? card.offsetWidth + 20 : Math.round(track.clientWidth * 0.85);
    }

    function updateButtons() {
        const maxScroll = track.scrollWidth - track.clientWidth;
        prev.disabled = track.scrollLeft <= 1;
        next.disabled = maxScroll <= 1 || track.scrollLeft >= maxScroll - 1;
    }

    prev.addEventListener('click', function () {
        track.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
    });
    next.addEventListener('click', function () {
        track.scrollBy({ left: scrollStep(), behavior: 'smooth' });
    });
    track.addEventListener('scroll', updateButtons, { passive: true });
    window.addEventListener('resize', updateButtons);
    track.querySelectorAll('img').forEach(function (img) {
        if (!img.complete) img.addEventListener('load', updateButtons);
    });
    updateButtons();
    setTimeout(updateButtons, 300);
});
</script>
@endpush

@endsection

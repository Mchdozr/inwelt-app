@extends('layouts.app')

@section('title', 'Ana Sayfa')
@section('description', 'INWELT — evden outdoor\'a binlerce ürün, uygun fiyat ve güvenilir alışveriş. Aradığınız her şey tek yerde.')

@section('content')

{{-- HERO --}}
<section class="hero-shell border-b border-iw-border">
    <div class="hero-shell__glow" aria-hidden="true"></div>
    <div class="relative max-w-[1200px] mx-auto px-6 py-16 md:py-24 lg:py-28 grid lg:grid-cols-2 gap-12 lg:gap-16 items-center hero-editorial">
        <div>
            <p class="hero-eyebrow mb-6">Aradığınız her şey, tek yerde</p>
            <h1>Uygun fiyatla <em>her şeye</em> ulaşın</h1>
            <p class="mt-6">
                Geniş ürün yelpazesi, şeffaf fiyatlar ve güvenilir alışveriş. Ev, hobi veya hediye — doğru ürünü hızlıca bulun.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-4">
                <a href="{{ route('products.index') }}" class="btn-primary">Ürünleri keşfet</a>
                <a href="{{ route('about') }}" class="btn-outline">Hakkımızda</a>
            </div>
            <div class="trust-inline mt-8 flex flex-wrap gap-2">
                <span class="trust-pill trust-pill--green">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Hızlı kargo
                </span>
                <span class="trust-pill trust-pill--blue">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Güvenli ödeme
                </span>
                <span class="trust-pill trust-pill--orange">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Kolay iletişim
                </span>
            </div>
            <div class="hero-stat-row">
                <div class="hero-stat"><strong>1000+</strong><span>Ürün çeşidi</span></div>
                <div class="hero-stat"><strong>7/24</strong><span>Online mağaza</span></div>
                <div class="hero-stat"><strong>%100</strong><span>Orijinal ürün</span></div>
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
<section class="section-surface border-b border-iw-border">
    <div class="max-w-[1200px] mx-auto px-6 py-12 md:py-16">
        <div class="section-head flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-8 md:mb-10">
            <div>
                <p class="label">Hızlı keşif</p>
                <h2>Popüler alışveriş alanları</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn-ghost text-sm shrink-0">Tümünü gör →</a>
        </div>
        <div class="explore-grid">
            @foreach([
                ['Fırsat Ürünleri', 'orange', 'deal', 'images/hero/rc-car.png', 'Fırsat'],
                ['Çok Satanlar', 'yellow', 'bestseller', 'images/hero/gimbal.png', 'Popüler'],
                ['Kargo Bedava', 'gray', 'free-shipping', 'images/hero/charger.png', 'Ücretsiz'],
                ['Hızlı Teslimat', 'green', 'fast-delivery', 'images/hero/rc-car.png', 'Hızlı'],
                ['Akıllı Cihazlar', 'blue', 'smart-devices', 'images/hero/charger.png', 'Teknoloji'],
                ['Hediye Fikirleri', 'pink', 'gift', 'images/hero/smart-ring.png', 'Hediye'],
            ] as [$title, $tone, $slug, $img, $badge])
            <a href="{{ route('products.index', ['filtre' => $slug]) }}" class="explore-card explore-card--{{ $tone }} group no-underline">
                <span class="explore-card__badge">{{ $badge }}</span>
                <h3 class="explore-card__title">{{ $title }}</h3>
                <div class="explore-card__visual">
                    <img src="{{ asset($img) }}" alt="" loading="lazy" decoding="async" aria-hidden="true">
                </div>
                <span class="explore-card__cta">Keşfet <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
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
            <h2>Popüler alışveriş alanları</h2>
            <p>Hızlıca göz atın, tek tıkla keşfedin.</p>
        </div>
        @php
            $categoryVisuals = [
                'akilli' => ['img' => 'images/hero/charger.png', 'tone' => 'blue', 'tag' => 'Yeni gelenler'],
                'cihaz' => ['img' => 'images/hero/charger.png', 'tone' => 'blue', 'tag' => 'Yeni gelenler'],
                'rc' => ['img' => 'images/hero/rc-car.png', 'tone' => 'orange', 'tag' => 'Çok satan'],
                'oyuncak' => ['img' => 'images/hero/rc-car.png', 'tone' => 'orange', 'tag' => 'Eğlence'],
                'muzik' => ['img' => 'images/hero/gimbal.png', 'tone' => 'purple', 'tag' => 'Trend'],
                'eglence' => ['img' => 'images/hero/gimbal.png', 'tone' => 'purple', 'tag' => 'Trend'],
                'zeka' => ['img' => 'images/hero/gimbal.png', 'tone' => 'yellow', 'tag' => 'Aile'],
                'egitim' => ['img' => 'images/hero/gimbal.png', 'tone' => 'yellow', 'tag' => 'Aile'],
                'guvenlik' => ['img' => 'images/hero/smart-ring.png', 'tone' => 'green', 'tag' => 'Outdoor'],
                'outdoor' => ['img' => 'images/hero/smart-ring.png', 'tone' => 'green', 'tag' => 'Outdoor'],
                'bakim' => ['img' => 'images/hero/smart-ring.png', 'tone' => 'pink', 'tag' => 'Günlük'],
                'kisisel' => ['img' => 'images/hero/smart-ring.png', 'tone' => 'pink', 'tag' => 'Günlük'],
            ];
            $resolveCategoryVisual = function ($slug) use ($categoryVisuals) {
                $slug = strtolower($slug);
                foreach ($categoryVisuals as $key => $visual) {
                    if (str_contains($slug, $key)) {
                        return $visual;
                    }
                }
                return ['img' => 'images/hero/hero-composite.png', 'tone' => 'gray', 'tag' => 'Keşfet'];
            };
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
            @foreach($categories as $cat)
            @php $visual = $resolveCategoryVisual($cat->slug); @endphp
            <a href="{{ route('products.category', $cat->slug) }}" class="cat-tile cat-tile--{{ $visual['tone'] }} group no-underline">
                <span class="cat-tile__badge">{{ $visual['tag'] }}</span>
                <div class="cat-tile__visual">
                    <img src="{{ asset($visual['img']) }}" alt="" loading="lazy" decoding="async" aria-hidden="true">
                </div>
                <div class="cat-tile__body">
                    <h3>{{ $cat->name }}</h3>
                    <span class="cat-tile__cta">Göz at <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                </div>
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
                <div class="prod-card__body">
                    @if($product->badge)
                    <span class="badge-deal self-start mb-2">{{ $product->badge }}</span>
                    @endif
                    <h3 class="prod-card__title">{{ $product->name }}</h3>
                    @if($product->summary)
                    <p class="text-iw-text-muted text-sm mt-2 line-clamp-2 flex-1">{{ $product->summary }}</p>
                    @endif
                    <span class="prod-card__cta">İncele →</span>
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
            <div class="relative grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <p class="text-sm font-semibold text-orange-300 mb-3">Yardıma mı ihtiyacınız var?</p>
                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Aradığınız ürünü birlikte bulalım</h2>
                    <p class="mt-4 opacity-75 max-w-md text-sm md:text-base">Stok, özellik veya sipariş hakkında sorularınız için ekibimiz yanınızda.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" class="cta-btn-primary">İletişime geç</a>
                        <a href="{{ route('products.index') }}" class="cta-btn-secondary">Ürünleri incele</a>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-3 text-sm">
                    @foreach([
                        ['M5 13l4 4L19 7', 'Hızlı kargo', 'Aynı gün kargoya teslim'],
                        ['M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'Güvenli ödeme', 'Taksit ve güvenli altyapı'],
                        ['M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'Geniş yelpaze', 'Akıllı cihazdan oyuncağa'],
                        ['M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'Kolay iletişim', 'Hızlı yanıt'],
                    ] as [$icon, $title, $desc])
                    <div class="cta-benefit">
                        <span class="cta-benefit__icon">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                        </span>
                        <div>
                            <div class="font-semibold">{{ $title }}</div>
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

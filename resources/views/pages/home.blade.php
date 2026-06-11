@extends('layouts.app')

@section('title', 'Ana Sayfa')
@section('description', 'INWELT — evden outdoor\'a binlerce ürün, uygun fiyat ve güvenilir alışveriş. Aradığınız her şey tek yerde.')

@section('content')

{{-- HERO --}}
<section class="border-b border-iw-border bg-iw-panel">
    <div class="max-w-[1200px] mx-auto px-6 py-16 md:py-24 lg:py-28 grid lg:grid-cols-2 gap-12 lg:gap-16 items-center hero-editorial">
        <div>
            <p class="hero-eyebrow text-sm font-semibold text-iw-brand mb-6">Aradığınız her şey!</p>
            <h1>Inwelt ile tüm ürünlere en uygun fiyata ulaşın!</h1>
            <p class="mt-6 text-iw-text-muted text-base md:text-lg leading-relaxed max-w-md">
                Günlük ihtiyaçlardan özel alışverişe — geniş ürün yelpazesi, şeffaf fiyatlar ve kolay sipariş. İster ev, ister hobi, ister hediye; doğru ürünü hızlıca bulun.
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
    <div class="max-w-[1200px] mx-auto px-6 py-10 md:py-12">
        <div class="flex items-center justify-between gap-4 mb-5 md:mb-6">
            <h2 class="text-lg md:text-xl font-bold text-iw-text tracking-tight">Keşfet</h2>
            <a href="{{ route('products.index') }}" class="btn-ghost text-sm">Tümü →</a>
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

@extends('layouts.app')

@section('title', 'Ana Sayfa')
@section('description', 'INWELT — evden outdoor\'a binlerce ürün, uygun fiyat ve güvenilir alışveriş. Aradığınız her şey tek yerde.')

@section('content')

{{-- HERO --}}
<section class="hero-shell border-b border-iw-border">
    <div class="hero-editorial">
        <div class="hero-editorial__copy">
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
            <img src="{{ asset($heroVisual['src']) }}" alt="{{ $heroVisual['alt'] }}" width="1024" height="1024" loading="eager" decoding="async">
        </div>
        @else
        @include('partials.hero-float-grid')
        @endif
    </div>
</section>

{{-- HAFTANIN SEÇİMLERİ --}}
@if($featured->count())
<section class="weekly-picks">
    <div class="site-container py-10 md:py-14">
        <div class="weekly-picks__head">
            <div class="weekly-picks__head-copy">
                <p class="weekly-picks__eyebrow">Öne çıkan</p>
                <h2 class="weekly-picks__title">Haftanın Seçimleri</h2>
                <p class="weekly-picks__subtitle">Bu hafta öne çıkan ürünler ve güncel fırsatlar</p>
            </div>
            <a href="{{ route('products.index') }}" class="weekly-picks__view-all">Tümünü gör</a>
        </div>
        <div class="weekly-picks-carousel carousel-wrap" id="weeklyPicksCarousel">
            <div class="carousel-track weekly-picks-track" id="weeklyPicksTrack">
                @foreach($featured as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="weekly-picks-card group no-underline">
                    <div class="weekly-picks-card__media">
                        @if($product->hasPriceDropBadge())
                        <span class="prod-card__stamp prod-card__stamp--compact">Fiyatı Düştü</span>
                        @endif
                        <x-product-image :src="$product->cover_image" :alt="$product->name" class="weekly-picks-card__img" />
                    </div>
                    <div class="weekly-picks-card__body">
                        <p class="weekly-picks-card__name">{{ $product->name }}</p>
                        <div class="weekly-picks-card__badges">
                            @if($product->is_advantageous || $product->badge)
                            <span class="weekly-picks-card__badge weekly-picks-card__badge--deal">Fırsat</span>
                            @endif
                            @if($product->badge)
                            <span class="weekly-picks-card__badge weekly-picks-card__badge--accent">{{ $product->badge }}</span>
                            @elseif($product->category)
                            <span class="weekly-picks-card__badge weekly-picks-card__badge--muted">{{ $product->category->name }}</span>
                            @endif
                        </div>
                        <span class="weekly-picks-card__link">İncele <svg class="weekly-picks-card__arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                    </div>
                </a>
                @endforeach
            </div>
            <button type="button" class="carousel-btn carousel-btn-prev weekly-picks-carousel__btn" id="weeklyPicksPrev" aria-label="Önceki">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" class="carousel-btn carousel-btn-next weekly-picks-carousel__btn" id="weeklyPicksNext" aria-label="Sonraki">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
</section>
@endif

{{-- KATEGORİLER --}}
@if($categories->count())
<section class="py-16 md:py-20">
    <div class="site-container">
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
                'zeka' => ['img' => 'products/tangram-zeka-seti/g1.webp', 'storage' => true, 'tone' => 'yellow', 'tag' => 'Aile'],
                'egitim' => ['img' => 'products/tangram-zeka-seti/g1.webp', 'storage' => true, 'tone' => 'yellow', 'tag' => 'Aile'],
                'guvenlik' => ['img' => 'images/hero/smart-ring.png', 'tone' => 'green', 'tag' => 'Outdoor'],
                'outdoor' => ['img' => 'images/hero/smart-ring.png', 'tone' => 'green', 'tag' => 'Outdoor'],
                'bakim' => ['img' => 'products/elektrikli-tirnak-kesici-beyaz/g1.webp', 'storage' => true, 'tone' => 'pink', 'tag' => 'Günlük'],
                'kisisel' => ['img' => 'products/elektrikli-tirnak-kesici-beyaz/g1.webp', 'storage' => true, 'tone' => 'pink', 'tag' => 'Günlük'],
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
        <div class="cat-grid">
            @foreach($categories as $cat)
            @php $visual = $resolveCategoryVisual($cat->slug); @endphp
            <a href="{{ route('products.category', $cat->slug) }}" class="cat-tile cat-tile--{{ $visual['tone'] }} group no-underline">
                <span class="cat-tile__badge">{{ $visual['tag'] }}</span>
                <div class="cat-tile__visual">
                    <img src="{{ ! empty($visual['storage']) ? Storage::url($visual['img']) : asset($visual['img']) }}" alt="" loading="lazy" decoding="async" aria-hidden="true">
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

{{-- CTA --}}
<section class="py-16 md:py-20">
    <div class="site-container">
        <div class="cta-panel p-8 md:p-12 lg:p-14">
            <div class="relative grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <p class="mb-3 text-sm font-medium text-slate-300">Yardıma mı ihtiyacınız var?</p>
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
    function initCarousel(trackId, prevId, nextId, cardSelector) {
        const track = document.getElementById(trackId);
        const prev = document.getElementById(prevId);
        const next = document.getElementById(nextId);
        if (!track || !prev || !next) return;

        function scrollStep() {
            const card = track.querySelector(cardSelector);
            return card ? card.offsetWidth + 16 : Math.round(track.clientWidth * 0.85);
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
    }

    initCarousel('weeklyPicksTrack', 'weeklyPicksPrev', 'weeklyPicksNext', '.weekly-picks-card');
});
</script>
@endpush

@endsection

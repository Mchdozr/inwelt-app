@extends('layouts.app')

@section('title', 'Ana Sayfa')
@section('description', 'INWELT — akıllı cihazlar, RC oyuncaklar, dijital müzik setleri, zeka oyunları ve güvenlik ürünleri. Hayatı kolaylaştıran teknolojiyi keşfedin.')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden border-b border-iw-border bg-iw-deep">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_20%,rgba(37,99,235,0.10)_0%,transparent_55%)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_15%_85%,rgba(249,115,22,0.08)_0%,transparent_50%)]"></div>

    <div class="relative max-w-[1200px] mx-auto px-6 py-20 lg:py-24 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="eyebrow-badge mb-5">
                <span class="dot"></span>
                Teknoloji &amp; Yaşam
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-[3.4rem] font-extrabold text-iw-text leading-tight tracking-[-0.03em]">
                Hayatı Kolaylaştıran<br><span class="text-iw-accent">Akıllı Teknoloji</span>
            </h1>
            <p class="mt-6 text-iw-text-muted text-lg leading-relaxed max-w-lg">
                Akıllı cihazlardan eğlenceli RC oyuncaklara, dijital müzik setlerinden zeka oyunlarına kadar; INWELT ile her gününüze renk ve pratiklik katın.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="btn-primary px-6 py-3 text-base">
                    Ürünleri Keşfet
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('about') }}" class="btn-outline px-6 py-3 text-base">Hakkımızda</a>
            </div>
            <div class="mt-10 grid grid-cols-3 gap-3 max-w-lg">
                @foreach([['Hızlı','Teslimat'],['Güvenli','Ödeme'],['Kolay','İade']] as [$value,$label])
                <div class="stat-card">
                    <div class="text-lg font-extrabold text-iw-text">{{ $value }}</div>
                    <div class="text-xs text-iw-text-muted mt-0.5">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="relative">
            <div class="grid grid-cols-2 gap-4">
                @foreach($featured->take(4) as $i => $product)
                <a href="{{ route('products.show', $product->slug) }}" class="prod-card group block no-underline {{ $i % 2 == 1 ? 'mt-6' : '' }}">
                    <div class="prod-card-media aspect-square">
                        <x-product-image :src="$product->cover_image" :alt="$product->name" class="prod-media" />
                    </div>
                    <div class="px-3 py-2.5">
                        <div class="text-xs font-semibold text-iw-text line-clamp-1">{{ $product->name }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- TRUST BAR --}}
<section class="border-b border-iw-border section-tint">
    <div class="max-w-[1200px] mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach([
            ['Hızlı Kargo','Aynı gün kargoya teslim'],
            ['Güvenli Ödeme','Taksit ve güvenli altyapı'],
            ['Geniş Yelpaze','Akıllı cihazdan oyuncağa'],
            ['Kolay İletişim','Sorularınıza hızlı yanıt']
        ] as [$title,$desc])
        <div class="flex items-start gap-3">
            <span class="mt-1 h-2 w-2 rounded-full bg-iw-accent flex-shrink-0"></span>
            <div>
                <div class="font-semibold text-iw-text text-sm">{{ $title }}</div>
                <div class="text-iw-text-muted text-xs mt-0.5">{{ $desc }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- HIZLI KEŞİF ŞERİDİ --}}
<section class="bg-iw-deep/70 border-b border-iw-border">
    <div class="max-w-[1200px] mx-auto px-6 py-6">
        <div class="market-rail px-4 py-4">
            <div class="flex items-center justify-between gap-4 mb-3">
                <h2 class="text-sm font-bold text-iw-text">Hızlı Keşif</h2>
                <a href="{{ route('products.index') }}" class="text-sm font-semibold text-iw-accent no-underline">Tümünü Gör</a>
            </div>
            <div class="flex gap-3 overflow-x-auto pb-1">
                @foreach([
                    ['Fırsat Ürünleri', 'Yeni', '⚡'],
                    ['Çok Satanlar', 'Popüler', '★'],
                    ['Kargo Bedava', 'Avantaj', '▣'],
                    ['Hızlı Teslimat', 'Stokta', '→'],
                    ['Akıllı Cihazlar', 'Tech', '⌁'],
                    ['STEM Oyuncaklar', 'Eğitici', '◆'],
                    ['Müzik & Eğlence', 'Hobi', '♪'],
                    ['Seyahat Dostu', 'Pratik', '✈'],
                ] as [$title, $tag, $icon])
                <a href="{{ route('products.index', ['ara' => $title]) }}" class="story-card">
                    <span class="story-icon">{{ $icon }}</span>
                    <span class="text-xs font-semibold text-iw-text leading-tight">{{ $title }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wide text-iw-amber">{{ $tag }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- KATEGORİLER --}}
@if($categories->count())
<section class="py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-title">
            <span class="eyebrow">Kategoriler</span>
            <h2>Ürün Gruplarımız</h2>
            <p>İlgi alanınıza uygun ürünleri kolayca keşfedin</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($categories as $cat)
            <a href="{{ route('products.category', $cat->slug) }}" class="iw-card group p-6 flex items-start gap-4 no-underline">
                <div class="icon-chip flex-shrink-0 group-hover:bg-iw-accent/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-iw-text group-hover:text-iw-accent transition-colors text-base">{{ $cat->name }}</h3>
                    @if($cat->description)
                    <p class="text-iw-text-muted text-sm mt-1 line-clamp-2">{{ $cat->description }}</p>
                    @endif
                    <div class="flex items-center gap-1 mt-3 text-iw-accent text-sm font-medium">
                        <span>Ürünleri Gör</span>
                        <svg class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ÖNE ÇIKAN ÜRÜNLER --}}
@if($featured->count())
<section class="py-20 section-soft border-y border-iw-border">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-title">
            <span class="eyebrow">Öne Çıkan</span>
            <h2>Popüler Ürünler</h2>
            <p>En çok tercih edilen ve yeni ürünlerimizi keşfedin</p>
        </div>
        <div class="carousel-wrap" id="featuredCarousel">
            <button type="button" class="carousel-btn carousel-btn-prev" id="featuredPrev" aria-label="Önceki">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" class="carousel-btn carousel-btn-next" id="featuredNext" aria-label="Sonraki">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div class="carousel-track" id="featuredTrack">
            @foreach($featured as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="prod-card group flex min-w-[280px] sm:min-w-[330px] lg:min-w-[360px] flex-col no-underline">
                <div class="prod-card-media">
                    <x-product-image :src="$product->cover_image" :alt="$product->name" class="prod-media" />
                </div>
                <div class="p-5 flex flex-col flex-1">
                    @if($product->badge)
                    <span class="self-start mb-2 text-xs font-bold px-2.5 py-1 rounded-full bg-iw-amber/10 text-iw-amber border border-iw-border">{{ $product->badge }}</span>
                    @endif
                    <h3 class="font-semibold text-iw-text group-hover:text-iw-accent transition-colors">{{ $product->name }}</h3>
                    @if($product->summary)
                    <p class="text-iw-text-muted text-sm mt-2 line-clamp-2 flex-1">{{ $product->summary }}</p>
                    @endif
                    <div class="flex items-center gap-1 mt-4 text-iw-accent text-sm font-medium">
                        <span>İncele</span>
                        <svg class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </a>
            @endforeach
            </div>
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('products.index') }}" class="btn-outline px-8 py-3">Tüm Ürünleri Gör</a>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="cta-panel p-8 md:p-12 lg:p-14">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_80%_20%,rgba(249,115,22,0.25)_0%,transparent_55%)]"></div>
            <div class="relative grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-bold tracking-widest uppercase text-white mb-4">Size Yardımcı Olalım</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Aradığınız ürünü birlikte bulalım</h2>
                    <p class="mt-4 text-white/85 max-w-lg">Ürünler, stok durumu veya sipariş hakkında her türlü sorunuz için bize ulaşın.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 text-base rounded-xl bg-white text-iw-accent font-semibold hover:bg-white/90 transition-colors no-underline">Hemen İletişime Geç</a>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 text-base rounded-xl border border-white/40 text-white font-semibold hover:bg-white/10 transition-colors no-underline">Ürünleri İncele</a>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach([
                        ['Hızlı Kargo', 'Aynı gün kargoya teslim', 'M13 10V3L4 14h7v7l9-11h-7z'],
                        ['Güvenli Ödeme', 'Taksit ve güvenli altyapı', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ['Geniş Yelpaze', 'Akıllı cihazdan oyuncağa', 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['Kolay İletişim', 'Sorularınıza hızlı yanıt', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                    ] as [$title, $desc, $path])
                    <div class="cta-benefit">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/20 text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/></svg>
                        </span>
                        <div>
                            <div class="font-semibold text-white">{{ $title }}</div>
                            <div class="text-sm text-white/75 mt-0.5">{{ $desc }}</div>
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
(function () {
    const track = document.getElementById('featuredTrack');
    const prev = document.getElementById('featuredPrev');
    const next = document.getElementById('featuredNext');
    if (!track || !prev || !next) return;

    function cardWidth() {
        const card = track.querySelector('.prod-card');
        if (!card) return 360;
        const gap = 24;
        return card.offsetWidth + gap;
    }

    function updateButtons() {
        const max = track.scrollWidth - track.clientWidth - 2;
        prev.disabled = track.scrollLeft <= 2;
        next.disabled = track.scrollLeft >= max;
    }

    prev.addEventListener('click', () => {
        track.scrollBy({ left: -cardWidth(), behavior: 'smooth' });
    });
    next.addEventListener('click', () => {
        track.scrollBy({ left: cardWidth(), behavior: 'smooth' });
    });
    track.addEventListener('scroll', updateButtons, { passive: true });
    window.addEventListener('resize', updateButtons);
    updateButtons();
})();
</script>
@endpush

@endsection

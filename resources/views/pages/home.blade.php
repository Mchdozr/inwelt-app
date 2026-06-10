@extends('layouts.app')

@section('title', 'Ana Sayfa')
@section('description', 'INWELT akıllı sistemler ve endüstriyel çözümler. Ürün kataloğumuzu keşfedin, teknik destek ve teklif için bize ulaşın.')

@section('content')

{{-- HERO --}}
<section class="relative min-h-[90vh] flex items-center overflow-hidden">
    {{-- BG gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-iw-deep via-[#0a1525] to-[#060a14]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_60%_50%,rgba(61,139,255,0.12)_0%,transparent_70%)]"></div>
    {{-- Grid pattern --}}
    <div class="absolute inset-0 opacity-[0.03]" style="background-image:url('data:image/svg+xml,<svg width=\"40\" height=\"40\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M 40 0 L 0 0 0 40\" fill=\"none\" stroke=\"white\" stroke-width=\"0.5\"/></svg>');background-size:40px 40px;"></div>

    <div class="relative max-w-[1200px] mx-auto px-6 py-24 grid lg:grid-cols-2 gap-16 items-center">
        <div>
            <span class="inline-block text-xs font-bold tracking-widest uppercase text-iw-accent mb-4">Kalpten Kalbe</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight">
                Geleceği Birlikte<br><span class="text-iw-accent">İnşa Ediyoruz</span>
            </h1>
            <p class="mt-6 text-iw-text-muted text-lg leading-relaxed max-w-lg">
                INWELT, akıllı sistemler ve endüstriyel çözümlerde güvenilir teknoloji ortağınız. Ürünlerimizi keşfedin, teknik destek ve teklif için bize ulaşın.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="btn-primary px-6 py-3 text-base">
                    Ürünleri Keşfet
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="btn-outline px-6 py-3 text-base">İletişime Geç</a>
            </div>
        </div>
        {{-- Hero visual --}}
        <div class="hidden lg:flex items-center justify-center">
            <div class="relative w-80 h-80">
                <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-iw-accent/20 to-transparent border border-iw-border animate-pulse-slow"></div>
                <div class="absolute inset-8 rounded-2xl bg-iw-card border border-iw-border flex items-center justify-center">
                    <span class="text-7xl font-extrabold tracking-tight text-white/10">IW</span>
                </div>
                <div class="absolute -top-4 -right-4 w-20 h-20 rounded-2xl bg-iw-accent/10 border border-iw-border flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-8 h-8 text-iw-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TRUST BAR --}}
<section class="border-y border-iw-border bg-iw-card/50">
    <div class="max-w-[1200px] mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach([['◆','Üstün Kalite','ISO standartlarında üretim'],['◉','Global Hizmet','20+ ülkede güvenilir çözüm'],['⚙','Teknik Destek','Uzman mühendis kadrosu'],['✓','Sertifikalı','CE · ISO 9001 uyumlu']] as [$icon,$title,$desc])
        <div class="flex items-center gap-4">
            <span class="text-2xl text-iw-accent">{{ $icon }}</span>
            <div>
                <div class="font-semibold text-iw-text text-sm">{{ $title }}</div>
                <div class="text-iw-text-muted text-xs mt-0.5">{{ $desc }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- KATEGORİLER --}}
@if($categories->count())
<section class="py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-title">
            <span class="eyebrow">Kategoriler</span>
            <h2>Ürün Gruplarımız</h2>
            <p>İhtiyacınıza uygun çözümü kolayca bulun</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($categories as $cat)
            <a href="{{ route('products.category', $cat->slug) }}" class="iw-card group p-6 flex items-start gap-4 no-underline">
                <div class="w-12 h-12 rounded-xl bg-iw-accent/10 border border-iw-border flex items-center justify-center flex-shrink-0 group-hover:bg-iw-accent/20 transition-colors">
                    <svg class="w-6 h-6 text-iw-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
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
<section class="py-20 bg-iw-card/30">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-title">
            <span class="eyebrow">Öne Çıkan</span>
            <h2>Öne Çıkan Ürünlerimiz</h2>
            <p>En popüler ve yeni ürünlerimizi keşfedin</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="iw-card group flex flex-col no-underline">
                <div class="aspect-[4/3] bg-iw-card-hover overflow-hidden">
                    @if($product->cover_image)
                    <img src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-iw-border">
                        <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="p-5 flex flex-col flex-1">
                    @if($product->badge)
                    <span class="self-start mb-2 text-xs font-bold px-2.5 py-1 rounded-full bg-iw-accent/10 text-iw-accent border border-iw-border">{{ $product->badge }}</span>
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
        <div class="mt-10 text-center">
            <a href="{{ route('products.index') }}" class="btn-outline px-8 py-3">Tüm Ürünleri Gör</a>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="rounded-3xl bg-gradient-to-br from-iw-card via-[#0f1e3a] to-iw-card border border-iw-border p-10 md:p-16 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(61,139,255,0.08)_0%,transparent_70%)]"></div>
            <div class="relative">
                <span class="inline-block text-xs font-bold tracking-widest uppercase text-iw-accent mb-4">Teklif & Destek</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Projeniz için doğru çözümü birlikte bulalım</h2>
                <p class="mt-4 text-iw-text-muted max-w-lg mx-auto">Teknik destek, fiyat teklifi veya ürün bilgisi için uzman ekibimizle iletişime geçin.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('contact') }}" class="btn-primary px-8 py-3 text-base">Hemen İletişime Geç</a>
                    <a href="{{ route('products.index') }}" class="btn-outline px-8 py-3 text-base">Ürünleri İncele</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

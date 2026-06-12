@extends('layouts.app')

@section('title', 'Hakkımızda')
@section('description', 'INWELT hakkında: akıllı cihazlar, oyuncaklar, müzik ve zeka oyunlarında hayatı kolaylaştıran teknoloji ürünleri sunuyoruz.')

@section('content')

<section class="page-hero py-16 md:py-20">
    <div class="relative max-w-[1200px] mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <span class="eyebrow-badge mb-4">Hakkımızda</span>
                <h1 class="text-4xl md:text-5xl font-bold text-iw-text tracking-tight">Teknolojiyi herkes için ulaşılabilir kılıyoruz</h1>
                <p class="mt-5 text-iw-text-muted text-lg max-w-xl leading-relaxed">INWELT; akıllı cihazlardan oyuncaklara, müzik setlerinden zeka oyunlarına kadar günlük hayatı kolaylaştıran ürünleri güvenilir alışveriş deneyimiyle sunar.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn-primary">Ürünleri keşfet</a>
                    <a href="{{ route('contact') }}" class="btn-outline">Bize ulaşın</a>
                </div>
            </div>
            <div class="about-hero-visual">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(255,255,255,0.25)_0%,transparent_55%)]"></div>
                <span class="relative text-5xl md:text-6xl font-bold tracking-tight text-white">In<span class="text-white/75">welt</span></span>
            </div>
        </div>
    </div>
</section>

<section class="py-16 md:py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-title">
            <span class="eyebrow">Değerlerimiz</span>
            <h2>Neden INWELT?</h2>
            <p>Güvenilir alışverişin temel taşları</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['Kaliteli Ürünler','Özenle seçilmiş, dayanıklı ve günlük kullanıma uygun ürünler.'],
                ['Uygun Fiyat','Bütçe dostu fiyatlar ve taksit imkânlarıyla erişilebilir teknoloji.'],
                ['Hızlı Kargo','Siparişleriniz aynı gün kargoya teslim, kısa sürede elinizde.'],
                ['Geniş Yelpaze','Akıllı cihaz, oyuncak, müzik ve zeka oyunlarında tek adres.'],
                ['Müşteri Desteği','Satış öncesi ve sonrası hızlı, samimi destek.'],
                ['Güvenli Alışveriş','Güvenli ödeme altyapısı ve kolay iade süreçleri.'],
            ] as [$title,$desc])
            <div class="value-card">
                <div class="value-card__icon">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="font-semibold text-iw-text mb-2">{{ $title }}</h3>
                <p class="text-iw-text-muted text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="stat-band">
    <div class="max-w-[1200px] mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-5 text-center">
        @foreach([['5+','Ürün Kategorisi'],['100%','Orijinal Ürün'],['7/24','Online Mağaza'],['Hızlı','Kargo & Teslimat']] as [$num,$label])
        <div class="stat-card">
            <div class="text-3xl md:text-4xl font-bold text-iw-brand">{{ $num }}</div>
            <div class="text-iw-text-muted text-sm mt-1">{{ $label }}</div>
        </div>
        @endforeach
    </div>
</section>

<section class="py-16 md:py-20">
    <div class="max-w-2xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-iw-text tracking-tight">Keşfetmeye hazır mısınız?</h2>
        <p class="mt-4 text-iw-text-muted">Size en uygun ürünü bulmak için kataloğumuza göz atın veya bize ulaşın.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('products.index') }}" class="btn-primary px-8">Ürünleri keşfet</a>
            <a href="{{ route('contact') }}" class="btn-outline px-8">İletişime geç</a>
        </div>
    </div>
</section>

@endsection

@extends('layouts.app')

@section('title', 'Hakkımızda')
@section('description', 'INWELT hakkında: akıllı cihazlar, oyuncaklar, müzik ve zeka oyunlarında hayatı kolaylaştıran teknoloji ürünleri sunuyoruz.')

@section('content')

<section class="page-hero py-20">
    <div class="relative max-w-[1200px] mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <span class="eyebrow-badge mb-4">Hakkımızda</span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-iw-text tracking-tight">Teknolojiyi Herkes İçin Eğlenceli Kılıyoruz</h1>
                <p class="mt-5 text-iw-text-muted text-lg max-w-xl">INWELT; akıllı cihazlardan eğlenceli oyuncaklara, dijital müzik setlerinden zeka oyunlarına kadar günlük hayatı kolaylaştıran ve renklendiren ürünleri sizinle buluşturur.</p>
            </div>
            <div class="relative overflow-hidden rounded-2xl border border-iw-border bg-gradient-to-br from-iw-accent to-iw-accent-glow aspect-[21/9] flex items-center justify-center">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(249,115,22,0.30)_0%,transparent_55%)]"></div>
                <span class="relative text-5xl md:text-6xl font-extrabold tracking-tight text-white">IN<span class="text-white/70">WELT</span></span>
            </div>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-title">
            <span class="eyebrow">Değerlerimiz</span>
            <h2>Neden INWELT?</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['Kaliteli Ürünler','Özenle seçilmiş, dayanıklı ve günlük kullanıma uygun teknoloji ürünleri.'],
                ['Uygun Fiyat','Bütçe dostu fiyatlar ve taksit imkânlarıyla herkese ulaşılabilir teknoloji.'],
                ['Hızlı Kargo','Siparişleriniz aynı gün kargoya teslim edilir, kısa sürede elinizde.'],
                ['Geniş Yelpaze','Akıllı cihaz, oyuncak, müzik ve zeka oyunlarında tek adresten alışveriş.'],
                ['Müşteri Desteği','Satış öncesi ve sonrası sorularınıza hızlı ve samimi destek.'],
                ['Güvenli Alışveriş','Güvenli ödeme altyapısı ve kolay iade ile gönül rahatlığıyla alışveriş.'],
            ] as [$title,$desc])
            <div class="iw-panel p-6">
                <div class="icon-chip mb-4">
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
    <div class="max-w-[1200px] mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        @foreach([['5','Ürün Kategorisi'],['100%','Orijinal Ürün'],['7/24','Online Mağaza'],['Hızlı','Kargo & Teslimat']] as [$num,$label])
        <div class="stat-card">
            <div class="text-3xl md:text-4xl font-semibold text-iw-brand">{{ $num }}</div>
            <div class="text-iw-text-muted text-sm mt-1">{{ $label }}</div>
        </div>
        @endforeach
    </div>
</section>

<section class="py-20 text-center">
    <div class="max-w-2xl mx-auto px-6">
        <h2 class="text-3xl font-extrabold text-iw-text">Hadi Keşfetmeye Başlayın</h2>
        <p class="mt-4 text-iw-text-muted">Size en uygun ürünü bulmak için kataloğumuza göz atın veya bize ulaşın.</p>
        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('products.index') }}" class="btn-primary px-8 py-3">Ürünleri Keşfet</a>
            <a href="{{ route('contact') }}" class="btn-outline px-8 py-3">İletişime Geç</a>
        </div>
    </div>
</section>

@endsection

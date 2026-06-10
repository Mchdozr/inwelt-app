@extends('layouts.app')

@section('title', 'Hakkımızda')
@section('description', 'INWELT hakkında: endüstriyel otomasyon, akıllı sistem çözümleri ve global proje deneyimimiz.')

@section('content')

{{-- HERO --}}
<section class="py-20 bg-gradient-to-b from-iw-card/50 to-transparent border-b border-iw-border">
    <div class="max-w-[1200px] mx-auto px-6 text-center">
        <span class="inline-block text-xs font-bold tracking-widest uppercase text-iw-accent mb-3">Hakkımızda</span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight">Tecnoloji ile Geleceği İnşa Ediyoruz</h1>
        <p class="mt-5 text-iw-text-muted text-lg max-w-2xl mx-auto">INWELT olarak, endüstriyel otomasyon ve akıllı sistem çözümlerinde müşterilerimize en yüksek kalitede teknoloji sunuyoruz.</p>
    </div>
</section>

{{-- DEĞERLER --}}
<section class="py-20">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="section-title">
            <span class="eyebrow">Değerlerimiz</span>
            <h2>Neden INWELT?</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['Kalite Odaklı','ISO 9001 ve CE sertifikasına sahip ürünlerimiz, uluslararası standartlarda üretilir.'],
                ['Müşteri Öncelikli','Her projeyi özgün ihtiyaçlarınıza göre tasarlar, uzun vadeli çözümler üretiriz.'],
                ['İnovatif Yaklaşım','Teknolojinin en güncel gelişmelerini takip ederek ürün portföyümüzü sürekli yenileriz.'],
                ['Global Deneyim','20+ ülkedeki proje deneyimimizle sektörün lider tedarikçileri arasındayız.'],
                ['Teknik Destek','Satış öncesi ve sonrası uzman mühendis kadromuz her zaman yanınızda.'],
                ['Sürdürülebilirlik','Çevreye duyarlı üretim süreçleri ve enerji verimliliği odaklı çözümler sunuyoruz.'],
            ] as [$title,$desc])
            <div class="bg-iw-card border border-iw-border rounded-2xl p-6">
                <div class="w-10 h-10 rounded-xl bg-iw-accent/10 border border-iw-border flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-iw-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="font-semibold text-iw-text mb-2">{{ $title }}</h3>
                <p class="text-iw-text-muted text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="py-16 bg-iw-card/30 border-y border-iw-border">
    <div class="max-w-[1200px] mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach([['20+','Yıllık Deneyim'],['500+','Proje Tamamlandı'],['20+','Ülke'],['98%','Müşteri Memnuniyeti']] as [$num,$label])
        <div>
            <div class="text-4xl font-extrabold text-iw-accent">{{ $num }}</div>
            <div class="text-iw-text-muted text-sm mt-1">{{ $label }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="py-20 text-center">
    <div class="max-w-2xl mx-auto px-6">
        <h2 class="text-3xl font-extrabold text-white">Birlikte Çalışalım</h2>
        <p class="mt-4 text-iw-text-muted">Projenizi hayata geçirmek için uzman ekibimizle iletişime geçin.</p>
        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('contact') }}" class="btn-primary px-8 py-3">İletişime Geç</a>
            <a href="{{ route('products.index') }}" class="btn-outline px-8 py-3">Ürünlerimiz</a>
        </div>
    </div>
</section>

@endsection

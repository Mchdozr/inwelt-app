@extends('layouts.app')

@section('title', 'Editoryal Politika | INWELT')
@section('description', 'INWELT içeriklerinin nasıl üretildiği, fiyatların nereden geldiği ve güncelleme sıklığı.')

@section('content')
<article class="site-container py-12 max-w-3xl prose-iw">
    <h1>Editoryal politika</h1>
    <p>INWELT içerikleri marka vitrinini doğru, güncel ve doğrulanabilir tutmak için üretilir. Bu sayfa Google E-E-A-T ve AI arama kaynaklığı için şeffaflık sağlar.</p>
    <h2>İçerik nasıl üretiliyor?</h2>
    <p>Ürün açıklamaları teknik özellikler, kullanım senaryoları ve pazaryeri sayfalarındaki kamuya açık bilgilerden derlenir. Rehberler INWELT editörleri tarafından yazılır; yapay zekâ yalnızca taslak ve yapı için kullanılır, yayın öncesi insan kontrolünden geçer.</p>
    <h2>Fiyat bilgisi</h2>
    <p>Fiyat aralıkları Kacmasa, Trendyol ve Hepsiburada ürün sayfalarından günlük senkronize edilir. 7 günden eski fiyatlar şemada gösterilmez. Ödeme anındaki kesin tutar her zaman satıcı sitesindedir.</p>
    <h2>Güncelleme</h2>
    <p>Katalog her ürün kaydında, rehberler yayın veya düzenlemede güncellenir. Son güncelleme tarihi sayfada görünür.</p>
    <h2>İletişim</h2>
    <p>Düzeltme talepleri için <a href="{{ route('contact') }}">iletişim formu</a> veya {{ \App\Support\SiteContact::EMAIL }} kullanılır.</p>
</article>
@endsection

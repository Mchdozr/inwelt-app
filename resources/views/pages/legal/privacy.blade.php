@extends('layouts.app')

@section('title', 'Gizlilik Politikası | INWELT')
@section('description', 'INWELT gizlilik politikası: hangi verileri topladığımız ve nasıl kullandığımız.')

@section('content')
<article class="site-container py-12 max-w-3xl prose-iw">
    <h1>Gizlilik politikası</h1>
    <p>INWELT (inwelt.com.tr) iletişim formu, analitik ve çerezler yoluyla sınırlı kişisel veri işler.</p>
    <h2>Toplanan veriler</h2>
    <ul>
        <li>İletişim formundaki ad, e-posta, mesaj</li>
        <li>GA4 / GTM ile sayfa görüntüleme ve tıklama olayları</li>
        <li>Tercih çerezi (tema ve çerez onayı)</li>
    </ul>
    <h2>Amaç</h2>
    <p>Taleplere yanıt vermek, siteyi iyileştirmek ve pazarlama performansını ölçmek.</p>
    <h2>Paylaşım</h2>
    <p>Veriler Google Analytics, GTM ve e-posta altyapısı dışında üçüncü kişilere satılmaz. Pazaryeri satın almaları ilgili satıcının kendi gizlilik politikasına tabidir.</p>
    <h2>Haklarınız</h2>
    <p>KVKK kapsamındaki talepleriniz için {{ \App\Support\SiteContact::EMAIL }} adresine yazabilirsiniz.</p>
</article>
@endsection

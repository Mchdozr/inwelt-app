@extends('layouts.app')

@section('title', 'KVKK Aydınlatma Metni | INWELT')
@section('description', '6698 sayılı KVKK kapsamında INWELT aydınlatma metni.')

@section('content')
<article class="site-container py-12 max-w-3xl prose-iw">
    <h1>KVKK aydınlatma metni</h1>
    <p>6698 sayılı Kişisel Verilerin Korunması Kanunu uyarınca veri sorumlusu INWELT’tir. Adres: {{ \App\Support\SiteContact::address() }}</p>
    <h2>İşlenen veriler</h2>
    <p>Kimlik ve iletişim verileri (ad, e-posta, isteğe bağlı telefon), işlem güvenliği verileri (IP, tarayıcı) ve pazarlama ölçüm verileri.</p>
    <h2>Hukuki sebep</h2>
    <p>Açık rıza, bir hakkın tesisi ve meşru menfaat. İletişim formu gönderimi talep üzerine sözleşmenin kurulması öncesi iletişimdir.</p>
    <h2>Aktarım</h2>
    <p>Barındırma, e-posta ve analitik hizmet sağlayıcılarına, yurt içi/yurt dışı sunuculara aktarılabilir.</p>
    <h2>Haklar</h2>
    <p>Kanunun 11. maddesindeki haklarınızı {{ \App\Support\SiteContact::EMAIL }} üzerinden kullanabilirsiniz.</p>
</article>
@endsection

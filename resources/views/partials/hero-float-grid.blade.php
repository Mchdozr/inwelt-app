{{-- 4 ürünlü salınımlı grid vitrin (hero-float). Şimdilik composite modunda kapalı; home.blade.php $heroShowcaseMode ile açılır. --}}
@php
    $heroFloatImages = [
        ['src' => 'images/hero/rc-car.png', 'alt' => 'RC off-road araç', 'delay' => '0s', 'duration' => '4.2s'],
        ['src' => 'images/hero/charger.png', 'alt' => 'Kablosuz şarj istasyonu', 'delay' => '0.7s', 'duration' => '4.8s'],
        ['src' => 'images/hero/gimbal.png', 'alt' => 'Akıllı gimbal', 'delay' => '1.4s', 'duration' => '5.1s'],
        ['src' => 'images/hero/smart-ring.png', 'alt' => 'Akıllı yüzük', 'delay' => '2.1s', 'duration' => '4.5s'],
    ];
@endphp
<div class="hero-float" aria-hidden="true">
    @foreach($heroFloatImages as $item)
    <div class="hero-float__item" style="--float-delay: {{ $item['delay'] }}; --float-duration: {{ $item['duration'] }};">
        <img src="{{ asset($item['src']) }}" alt="{{ $item['alt'] }}" width="440" height="440" loading="eager" decoding="async">
    </div>
    @endforeach
</div>

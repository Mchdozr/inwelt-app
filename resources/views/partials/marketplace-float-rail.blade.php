@php
    $productSlug = isset($product) ? $product->slug : null;
    $kacmasaUrl = isset($product)
        ? \App\Support\ProductMarketplace::resolveKacmasaUrl($product)
        : \App\Support\ProductMarketplace::kacmasaStoreUrl();
    $trendyolUrl = isset($product)
        ? \App\Support\ProductMarketplace::trendyolUrl($product)
        : \App\Support\ProductMarketplace::trendyolStoreUrl();
    $hepsiburadaUrl = isset($product)
        ? \App\Support\ProductMarketplace::hepsiburadaUrl($product)
        : \App\Support\ProductMarketplace::hepsiburadaStoreUrl();
@endphp
<aside class="marketplace-float-rail" aria-label="Pazaryeri bağlantıları">
    <a
        href="{{ $kacmasaUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="marketplace-float-rail__btn marketplace-float-rail__btn--kacmasa"
        aria-label="Kacmasa'da incele"
        data-track-marketplace="kacmasa"
        @if($productSlug) data-product-slug="{{ $productSlug }}" @endif
        style="--rail-delay: 0.08s"
    >
        <img src="{{ asset('images/kacmasa-logo.png') }}" alt="" class="marketplace-float-rail__logo marketplace-float-rail__logo--theme-light" width="120" height="26" decoding="async" aria-hidden="true">
        <img src="{{ asset('images/kacmasa-logo-dark.png') }}" alt="" class="marketplace-float-rail__logo marketplace-float-rail__logo--theme-dark" width="120" height="26" decoding="async" aria-hidden="true">
        <span class="sr-only">Kacmasa</span>
    </a>

    <a
        href="{{ $trendyolUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="marketplace-float-rail__btn marketplace-float-rail__btn--trendyol"
        aria-label="Trendyol'da ara"
        data-track-marketplace="trendyol"
        @if($productSlug) data-product-slug="{{ $productSlug }}" @endif
        style="--rail-delay: 0.18s"
    >
        <img src="{{ asset('images/trendyol-logo.png') }}" alt="" class="marketplace-float-rail__logo" width="96" height="24" decoding="async" aria-hidden="true">
        <span class="sr-only">Trendyol</span>
    </a>

    <a
        href="{{ $hepsiburadaUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="marketplace-float-rail__btn marketplace-float-rail__btn--hepsiburada"
        aria-label="Hepsiburada'da ara"
        data-track-marketplace="hepsiburada"
        @if($productSlug) data-product-slug="{{ $productSlug }}" @endif
        style="--rail-delay: 0.28s"
    >
        <img src="{{ asset('images/hepsiburada-logo.svg') }}" alt="" class="marketplace-float-rail__logo" width="112" height="22" decoding="async" aria-hidden="true">
        <span class="sr-only">Hepsiburada</span>
    </a>
</aside>

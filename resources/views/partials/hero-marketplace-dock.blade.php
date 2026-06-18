@php
    $productSlug = isset($product) ? $product->slug : null;
    $kacmasaUrl = \App\Support\ProductMarketplace::kacmasaStoreUrl($productSlug);
    $trendyolUrl = isset($product)
        ? \App\Support\ProductMarketplace::trendyolUrl($product)
        : \App\Support\ProductMarketplace::trendyolStoreUrl();
    $hepsiburadaUrl = isset($product)
        ? \App\Support\ProductMarketplace::hepsiburadaUrl($product)
        : \App\Support\ProductMarketplace::hepsiburadaStoreUrl();
@endphp
<aside class="hero-marketplace-dock" aria-label="Pazaryeri bağlantıları">
    <p class="hero-marketplace-dock__title">Mağazalar</p>
    <div class="hero-marketplace-dock__links">
        <a
            href="{{ $kacmasaUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="hero-marketplace-dock__link"
            aria-label="Kacmasa mağazasında incele"
            data-track-marketplace="kacmasa"
            @if($productSlug) data-product-slug="{{ $productSlug }}" @endif
        >
            <img src="{{ asset('images/kacmasa-logo.png') }}" alt="" class="hero-marketplace-dock__logo hero-marketplace-dock__logo--theme-light" width="120" height="26" decoding="async" aria-hidden="true">
            <img src="{{ asset('images/kacmasa-logo-dark.png') }}" alt="" class="hero-marketplace-dock__logo hero-marketplace-dock__logo--theme-dark" width="120" height="26" decoding="async" aria-hidden="true">
            <span class="sr-only">Kacmasa</span>
        </a>

        <a
            href="{{ $trendyolUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="hero-marketplace-dock__link"
            aria-label="Trendyol'da ara"
            data-track-marketplace="trendyol"
            @if($productSlug) data-product-slug="{{ $productSlug }}" @endif
        >
            <img src="{{ asset('images/trendyol-logo.png') }}" alt="" class="hero-marketplace-dock__logo" width="96" height="24" decoding="async" aria-hidden="true">
            <span class="sr-only">Trendyol</span>
        </a>

        <a
            href="{{ $hepsiburadaUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="hero-marketplace-dock__link"
            aria-label="Hepsiburada'da ara"
            data-track-marketplace="hepsiburada"
            @if($productSlug) data-product-slug="{{ $productSlug }}" @endif
        >
            <img src="{{ asset('images/hepsiburada-logo.svg') }}" alt="" class="hero-marketplace-dock__logo" width="112" height="22" decoding="async" aria-hidden="true">
            <span class="sr-only">Hepsiburada</span>
        </a>
    </div>
</aside>

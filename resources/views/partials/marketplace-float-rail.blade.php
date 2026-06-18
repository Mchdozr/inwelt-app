<aside class="marketplace-float-rail" aria-label="Pazaryeri bağlantıları">
    <a
        href="{{ \App\Support\ProductMarketplace::kacmasaStoreUrl($product->slug) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="marketplace-float-rail__btn marketplace-float-rail__btn--kacmasa"
        aria-label="Kacmasa mağazasında incele"
        data-track-marketplace="kacmasa"
        data-product-slug="{{ $product->slug }}"
        style="--rail-delay: 0.08s"
    >
        <img src="{{ asset('images/kacmasa-logo.png') }}" alt="" class="marketplace-float-rail__logo marketplace-float-rail__logo--theme-light" width="120" height="26" decoding="async" aria-hidden="true">
        <img src="{{ asset('images/kacmasa-logo-dark.png') }}" alt="" class="marketplace-float-rail__logo marketplace-float-rail__logo--theme-dark" width="120" height="26" decoding="async" aria-hidden="true">
        <span class="sr-only">Kacmasa</span>
    </a>

    <a
        href="{{ \App\Support\ProductMarketplace::trendyolUrl($product) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="marketplace-float-rail__btn marketplace-float-rail__btn--trendyol"
        aria-label="Trendyol'da ara"
        data-track-marketplace="trendyol"
        data-product-slug="{{ $product->slug }}"
        style="--rail-delay: 0.18s"
    >
        <img src="{{ asset('images/trendyol-logo.png') }}" alt="" class="marketplace-float-rail__logo" width="96" height="24" decoding="async" aria-hidden="true">
        <span class="sr-only">Trendyol</span>
    </a>

    <a
        href="{{ \App\Support\ProductMarketplace::hepsiburadaUrl($product) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="marketplace-float-rail__btn marketplace-float-rail__btn--hepsiburada"
        aria-label="Hepsiburada'da ara"
        data-track-marketplace="hepsiburada"
        data-product-slug="{{ $product->slug }}"
        style="--rail-delay: 0.28s"
    >
        <img src="{{ asset('images/hepsiburada-logo.svg') }}" alt="" class="marketplace-float-rail__logo" width="112" height="22" decoding="async" aria-hidden="true">
        <span class="sr-only">Hepsiburada</span>
    </a>
</aside>

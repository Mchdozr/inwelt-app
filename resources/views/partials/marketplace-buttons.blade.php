<div class="marketplace-buttons">
    @if($kacmasaUrl = \App\Support\ProductMarketplace::kacmasaUrl($product))
    <a
        href="{{ $kacmasaUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="btn-marketplace"
        aria-label="Kacmasa'da incele"
        data-track-marketplace="kacmasa"
        data-product-slug="{{ $product->slug }}"
    >
        <img src="{{ asset('images/kacmasa-logo.png') }}" alt="" class="btn-marketplace__logo btn-marketplace__logo--kacmasa btn-marketplace__logo--theme-light" width="150" height="32" decoding="async" aria-hidden="true">
        <img src="{{ asset('images/kacmasa-logo-dark.png') }}" alt="" class="btn-marketplace__logo btn-marketplace__logo--kacmasa btn-marketplace__logo--theme-dark" width="150" height="32" decoding="async" aria-hidden="true">
        <span class="sr-only">Kacmasa</span>
    </a>
    @endif

    <a
        href="{{ \App\Support\ProductMarketplace::trendyolUrl($product) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="btn-marketplace"
        aria-label="Trendyol'da ara"
        data-track-marketplace="trendyol"
        data-product-slug="{{ $product->slug }}"
    >
        <img src="{{ asset('images/trendyol-logo.png') }}" alt="" class="btn-marketplace__logo btn-marketplace__logo--trendyol" width="110" height="28" decoding="async" aria-hidden="true">
        <span class="sr-only">Trendyol</span>
    </a>

    <a
        href="{{ \App\Support\ProductMarketplace::hepsiburadaUrl($product) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="btn-marketplace"
        aria-label="Hepsiburada'da ara"
        data-track-marketplace="hepsiburada"
        data-product-slug="{{ $product->slug }}"
    >
        <img src="{{ asset('images/hepsiburada-logo.svg') }}" alt="" class="btn-marketplace__logo btn-marketplace__logo--hepsiburada" width="130" height="24" decoding="async" aria-hidden="true">
        <span class="sr-only">Hepsiburada</span>
    </a>
</div>

<div class="marketplace-buttons flex flex-wrap gap-3">
    @if($product->seller_url)
    <a href="{{ $product->seller_url }}" target="_blank" rel="noopener noreferrer" class="btn-marketplace" aria-label="Kacmasa'da incele">
        <img src="{{ asset('images/kacmasa-logo.png') }}" alt="" class="btn-marketplace__logo btn-marketplace__logo--kacmasa" width="150" height="32" decoding="async" aria-hidden="true">
        <span class="sr-only">Kacmasa</span>
    </a>
    @endif

    <a href="{{ \App\Support\ProductMarketplace::trendyolUrl($product) }}" target="_blank" rel="noopener noreferrer" class="btn-marketplace" aria-label="Trendyol'da ara">
        <img src="{{ asset('images/trendyol-logo.svg') }}" alt="" class="btn-marketplace__logo btn-marketplace__logo--trendyol" width="110" height="28" decoding="async" aria-hidden="true">
        <span class="sr-only">Trendyol</span>
    </a>

    <a href="{{ \App\Support\ProductMarketplace::hepsiburadaUrl($product) }}" target="_blank" rel="noopener noreferrer" class="btn-marketplace" aria-label="Hepsiburada'da ara">
        <img src="{{ asset('images/hepsiburada-logo.svg') }}" alt="" class="btn-marketplace__logo btn-marketplace__logo--hepsiburada" width="130" height="24" decoding="async" aria-hidden="true">
        <span class="sr-only">Hepsiburada</span>
    </a>
</div>

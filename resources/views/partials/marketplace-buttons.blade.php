<div class="marketplace-buttons">
    @if($kacmasaUrl = \App\Support\ProductMarketplace::kacmasaUrl($product))
    <div class="marketplace-buttons__item group/mp">
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
        @if($kacmasaPrice = $product->marketplacePriceLabel('kacmasa'))
        @php($kacmasaHasPrice = (bool) $product->marketplacePrice('kacmasa'))
        <div @class(['marketplace-buttons__price', 'marketplace-buttons__price--pending' => ! $kacmasaHasPrice]) aria-label="Kacmasa fiyatı">
            @if($kacmasaHasPrice)
            <span class="marketplace-buttons__price-amount">{{ rtrim($kacmasaPrice, ' ₺') }}</span>
            <span class="marketplace-buttons__price-currency" aria-hidden="true">₺</span>
            @else
            <span class="marketplace-buttons__pending-indicator" aria-hidden="true"></span>
            <span class="marketplace-buttons__pending-text">{{ $kacmasaPrice }}</span>
            @endif
        </div>
        @endif
    </div>
    @endif

    <div class="marketplace-buttons__item group/mp">
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
        @if($trendyolPrice = $product->marketplacePriceLabel('trendyol'))
        @php($trendyolHasPrice = (bool) $product->marketplacePrice('trendyol'))
        <div @class(['marketplace-buttons__price', 'marketplace-buttons__price--pending' => ! $trendyolHasPrice]) aria-label="Trendyol fiyatı">
            @if($trendyolHasPrice)
            <span class="marketplace-buttons__price-amount">{{ rtrim($trendyolPrice, ' ₺') }}</span>
            <span class="marketplace-buttons__price-currency" aria-hidden="true">₺</span>
            @else
            <span class="marketplace-buttons__pending-indicator" aria-hidden="true"></span>
            <span class="marketplace-buttons__pending-text">{{ $trendyolPrice }}</span>
            @endif
        </div>
        @endif
    </div>

    <div class="marketplace-buttons__item group/mp">
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
        @if($hepsiburadaPrice = $product->marketplacePriceLabel('hepsiburada'))
        @php($hepsiburadaHasPrice = (bool) $product->marketplacePrice('hepsiburada'))
        <div @class(['marketplace-buttons__price', 'marketplace-buttons__price--pending' => ! $hepsiburadaHasPrice]) aria-label="Hepsiburada fiyatı">
            @if($hepsiburadaHasPrice)
            <span class="marketplace-buttons__price-amount">{{ rtrim($hepsiburadaPrice, ' ₺') }}</span>
            <span class="marketplace-buttons__price-currency" aria-hidden="true">₺</span>
            @else
            <span class="marketplace-buttons__pending-indicator" aria-hidden="true"></span>
            <span class="marketplace-buttons__pending-text">{{ $hepsiburadaPrice }}</span>
            @endif
        </div>
        @endif
    </div>
</div>

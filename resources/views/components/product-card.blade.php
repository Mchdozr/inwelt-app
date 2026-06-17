@props(['product', 'compact' => false])

<a href="{{ route('products.show', $product->slug) }}" {{ $attributes->merge(['class' => 'prod-card group flex flex-col no-underline']) }}>
    <div class="prod-card-media">
        @if($product->hasPriceDropBadge())
        <span class="prod-card__stamp">Fiyatı Düştü</span>
        @endif
        <x-product-image :src="$product->cover_image" :alt="$product->name" class="prod-media" />
    </div>
    <div class="prod-card__body">
        @unless($compact)
        <div class="prod-card__meta">
            <span class="tag-pill tag-pill--blue">{{ $product->category->name }}</span>
            @if($product->badge)
            <span class="badge-deal">{{ $product->badge }}</span>
            @endif
        </div>
        @endunless
        <h2 class="prod-card__title line-clamp-2 {{ $compact ? '' : 'min-h-[2.75rem]' }}">{{ $product->name }}</h2>
        @unless($compact)
        @if($product->summary)
        <p class="prod-card__summary">{{ $product->summary }}</p>
        @endif
        <div class="prod-card__footer">
            <span class="text-sm font-semibold text-iw-brand">Ürünü incele</span>
            <span class="prod-card__action" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        </div>
        @else
        <span class="prod-card__cta">İncele →</span>
        @endunless
    </div>
</a>

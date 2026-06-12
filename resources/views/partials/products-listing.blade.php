<div id="products-listing" class="products-listing" data-products-listing>
    @if($products->count())
    @php
        $toolbarFilter = request('filtre');
        $toolbarFilterLabel = $toolbarFilter && \App\Support\ProductFilters::isValid($toolbarFilter)
            ? \App\Support\ProductFilters::LABELS[$toolbarFilter]
            : null;
    @endphp
    <div class="listing-toolbar">
        <p class="listing-toolbar__count text-sm text-iw-text-muted">
            @if($toolbarFilterLabel)
            <span class="active-filter-label mr-2">{{ $toolbarFilterLabel }}</span>
            @endif
            <strong data-products-count>{{ $products->total() }}</strong> ürün listeleniyor
        </p>
        <div class="listing-tags">
            @foreach([
                ['Yeni Gelenler', 'orange'],
                ['Popüler', 'yellow'],
                ['Akıllı Yaşam', 'blue'],
            ] as [$tag, $tone])
            <span class="tag-pill tag-pill--{{ $tone }}">{{ $tag }}</span>
            @endforeach
        </div>
    </div>
    <div class="products-grid">
        @foreach($products as $product)
        <a href="{{ route('products.show', $product->slug) }}" class="prod-card group flex flex-col no-underline">
            <div class="prod-card-media">
                <x-product-image :src="$product->cover_image" :alt="$product->name" class="prod-media" />
            </div>
            <div class="prod-card__body">
                <div class="prod-card__meta">
                    <span class="tag-pill tag-pill--blue">{{ $product->category->name }}</span>
                    @if($product->badge)
                    <span class="badge-deal">{{ $product->badge }}</span>
                    @endif
                </div>
                <h2 class="prod-card__title line-clamp-2 min-h-[2.75rem]">{{ $product->name }}</h2>
                @if($product->summary)
                <p class="text-iw-text-muted text-sm line-clamp-2 flex-1">{{ $product->summary }}</p>
                @endif
                <div class="prod-card__footer">
                    <span class="text-sm font-semibold text-iw-brand">Ürünü incele</span>
                    <span class="prod-card__action" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    @if($products->hasPages())
    <div class="mt-10">
        {{ $products->links() }}
    </div>
    @endif

    @else
    <div class="empty-state">
        <div class="empty-state__icon">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-iw-text">Ürün bulunamadı</h3>
        <p class="mt-2 text-sm text-iw-text-muted">Bu filtrede henüz ürün yok. Farklı bir kategori deneyin.</p>
        <a href="{{ route('products.index') }}" class="btn-primary mt-6">Tüm ürünlere dön</a>
    </div>
    @endif
</div>

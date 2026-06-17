<div
    id="products-listing"
    class="products-listing"
    data-products-listing
    data-infinite-scroll
    data-products-total="{{ $products->total() }}"
    data-current-page="{{ $products->currentPage() }}"
    data-has-more="{{ $products->hasMorePages() ? 'true' : 'false' }}"
>
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
    </div>
    <div class="products-grid" data-products-grid>
        @foreach($products as $product)
        <x-product-card :product="$product" />
        @endforeach
    </div>

    @if($products->hasMorePages())
    <div class="products-infinite-sentinel" data-infinite-sentinel aria-hidden="true">
        <span class="products-infinite-sentinel__spinner" data-infinite-spinner hidden></span>
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

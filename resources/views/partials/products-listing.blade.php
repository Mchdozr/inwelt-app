<div id="products-listing" class="products-listing" data-products-listing>
    @if($products->count())
    <div class="market-rail mb-6 p-4">
        <div class="flex flex-wrap items-center gap-2">
            @foreach([
                ['Yeni Gelenler', 'orange'],
                ['Popüler Seçimler', 'yellow'],
                ['Akıllı Yaşam', 'blue'],
                ['Eğitici Oyuncak', 'green'],
                ['Günlük Pratiklik', 'gray'],
            ] as [$tag, $tone])
            <span class="tag-pill tag-pill--{{ $tone }}">{{ $tag }}</span>
            @endforeach
        </div>
    </div>
    <div class="mb-5 flex items-center justify-between gap-3">
        <p class="text-sm text-iw-text-muted"><span class="font-semibold text-iw-text" data-products-count>{{ $products->total() }}</span> ürün listeleniyor</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($products as $product)
        <a href="{{ route('products.show', $product->slug) }}" class="prod-card group flex flex-col no-underline">
            <div class="prod-card-media">
                <x-product-image :src="$product->cover_image" :alt="$product->name" class="prod-media" />
            </div>
            <div class="p-5 flex flex-col flex-1 gap-2">
                <div class="flex items-center justify-between gap-2">
                    <span class="tag-pill tag-pill--blue">{{ $product->category->name }}</span>
                    @if($product->badge)
                    <span class="badge-deal">{{ $product->badge }}</span>
                    @endif
                </div>
                <h2 class="font-semibold text-iw-text leading-snug group-hover:text-iw-accent transition-colors line-clamp-2 min-h-[2.75rem]">{{ $product->name }}</h2>
                @if($product->summary)
                <p class="text-iw-text-muted text-sm line-clamp-2 flex-1">{{ $product->summary }}</p>
                @endif
                <div class="mt-2 pt-3 border-t border-iw-border flex items-center justify-between gap-2">
                    <span class="text-iw-brand text-sm font-semibold">Ürünü İncele</span>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-orange-50 text-iw-brand group-hover:bg-iw-brand group-hover:text-white transition-colors">
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
    <div class="iw-panel text-center py-20 text-iw-text-muted">
        <div class="icon-chip mx-auto mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <p>Bu kategoride henüz ürün bulunmuyor.</p>
    </div>
    @endif
</div>

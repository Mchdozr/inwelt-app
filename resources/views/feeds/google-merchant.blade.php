<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>INWELT</title>
        <link>{{ url('/') }}</link>
        <description>INWELT ürün vitrini — Kacmasa, Trendyol ve Hepsiburada en düşük fiyatlar.</description>
        @foreach($products as $product)
        @php
            $image = \App\Support\GoogleMerchantFeed::imageUrl($product->cover_image);
            $extraImages = $product->images->take(9)->pluck('path')->filter();
            $description = trim(preg_replace('/\s+/', ' ', strip_tags((string) ($product->summary ?: $product->seo_description ?: $product->name))) ?? '');
        @endphp
        <item>
            <g:id>{{ $product->resolvedSku() }}</g:id>
            <g:title>{{ $product->name }}</g:title>
            <g:description>{{ $description }}</g:description>
            <g:link>{{ route('products.show', $product->slug) }}</g:link>
            @if($image)
            <g:image_link>{{ $image }}</g:image_link>
            @endif
            @foreach($extraImages as $path)
            @if($path !== $product->cover_image)
            <g:additional_image_link>{{ \App\Support\GoogleMerchantFeed::imageUrl($path) }}</g:additional_image_link>
            @endif
            @endforeach
            <g:availability>in stock</g:availability>
            <g:price>{{ number_format($product->lowestRawPrice(), 2, '.', '') }} TRY</g:price>
            <g:brand>INWELT</g:brand>
            <g:condition>new</g:condition>
            <g:mpn>{{ $product->resolvedSku() }}</g:mpn>
            @if(filled($product->gtin13))
            <g:gtin>{{ $product->gtin13 }}</g:gtin>
            @endif
            @if($product->category)
            <g:product_type>{{ $product->category->name }}</g:product_type>
            @endif
        </item>
        @endforeach
    </channel>
</rss>

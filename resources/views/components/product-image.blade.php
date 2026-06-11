@props([
    'src' => null,
    'alt' => '',
    'class' => 'w-full h-full object-cover',
    'lazy' => true,
])

@if($src)
<img
    src="{{ Storage::url($src) }}"
    alt="{{ $alt }}"
    class="{{ $class }}"
    @if($lazy) loading="lazy" decoding="async" @endif
    {{ $attributes }}
>
@else
<img
    src="{{ asset('images/product-fallback.png') }}"
    alt="{{ $alt ?: 'INWELT ürün görseli' }}"
    class="{{ $class }} product-fallback"
    @if($lazy) loading="lazy" decoding="async" @endif
    {{ $attributes }}
>
@endif

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
<div {{ $attributes->merge(['class' => 'w-full h-full flex items-center justify-center text-iw-border']) }}>
    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
</div>
@endif

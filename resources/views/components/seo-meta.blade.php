@props([
    'title' => 'INWELT',
    'description' => 'INWELT, akıllı sistemler ve endüstriyel çözümlerde güvenilir teknoloji ortağınız.',
    'image' => null,
    'type' => 'website',
    'robots' => 'index, follow',
])

@php
    $fullTitle = str_contains($title, 'INWELT') ? $title : "{$title} - INWELT Teknoloji";
    $canonical = url()->current();
    $ogImage = $image
        ? (str_starts_with($image, 'http') ? $image : url(Storage::url($image)))
        : null;
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ Str::limit($description, 160, '') }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ Str::limit($description, 160, '') }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:site_name" content="INWELT">
<meta property="og:locale" content="tr_TR">
@if($ogImage)
<meta property="og:image" content="{{ $ogImage }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ Str::limit($description, 160, '') }}">
@if($ogImage)
<meta name="twitter:image" content="{{ $ogImage }}">
@endif

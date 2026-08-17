@props([
    'title' => 'INWELT',
    'description' => 'INWELT, akıllı sistemler ve endüstriyel çözümlerde güvenilir teknoloji ortağınız.',
    'image' => null,
    'type' => 'website',
    'robots' => 'index, follow',
    'canonical' => null,
])

@php
    $fullTitle = str_contains($title, 'INWELT') ? $title : "{$title} - INWELT Teknoloji";
    $canonicalUrl = $canonical ?: url()->current();
    $ogImage = $image
        ? (str_starts_with($image, 'http') ? $image : url(\Illuminate\Support\Facades\Storage::url($image)))
        : asset('images/inwelt-logo.png');
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ Str::limit($description, 160, '') }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<link rel="alternate" hreflang="tr" href="{{ $canonicalUrl }}">
<link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ Str::limit($description, 160, '') }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:site_name" content="INWELT">
<meta property="og:locale" content="tr_TR">
<meta property="og:image" content="{{ $ogImage }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ Str::limit($description, 160, '') }}">
<meta name="twitter:image" content="{{ $ogImage }}">

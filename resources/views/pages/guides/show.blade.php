@extends('layouts.app')

@section('title', $guide['title'].' | INWELT Rehber')
@section('description', $guide['excerpt'])

@section('content')
<article class="site-container py-10 max-w-3xl">
    <nav class="breadcrumb mb-6 reveal" aria-label="Konum">
        <a href="{{ route('home') }}">Ana Sayfa</a>
        <svg class="breadcrumb__sep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('guides.index') }}">Rehberler</a>
    </nav>

    <h1 class="text-3xl font-bold text-iw-text font-display reveal">{{ $guide['title'] }}</h1>
    <p class="text-iw-text-muted mt-3 leading-relaxed reveal" style="--reveal-delay: 0.08s">{{ $guide['body'] }}</p>

    <div class="mt-8 flex flex-wrap gap-3 reveal" style="--reveal-delay: 0.14s">
        <a href="{{ route('products.index') }}" class="btn-primary">Ürünleri incele</a>
        <a href="{{ route('guides.index') }}" class="btn-outline">Tüm rehberler</a>
    </div>
</article>
@endsection

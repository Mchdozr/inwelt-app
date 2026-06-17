@extends('layouts.app')

@section('title', 'Alışveriş Rehberleri | INWELT')
@section('description', 'INWELT ürün kategorileri için kısa alışveriş ve seçim rehberleri.')

@section('content')
<section class="page-hero">
    <div class="site-container py-12">
        <span class="eyebrow-badge">Rehberler</span>
        <h1 class="text-3xl font-bold text-iw-text mt-3">Alışveriş Rehberleri</h1>
        <p class="text-iw-text-muted mt-2 max-w-2xl">Doğru ürünü seçmenize yardımcı olacak kısa içerikler.</p>
    </div>
</section>

<div class="site-container py-10">
    <div class="grid gap-4 md:grid-cols-3">
        @foreach($guides as $slug => $guide)
        <a href="{{ route('guides.show', $slug) }}" class="guide-card">
            <h2 class="guide-card__title">{{ $guide['title'] }}</h2>
            <p class="guide-card__excerpt">{{ $guide['excerpt'] }}</p>
            <span class="guide-card__link">Oku →</span>
        </a>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Alışveriş Rehberleri | INWELT')
@section('description', 'INWELT ürün kategorileri için alışveriş, karşılaştırma ve seçim rehberleri.')

@section('content')
<section class="page-hero">
    <div class="relative site-container py-12">
        <h1 class="reveal">Alışveriş Rehberleri</h1>
        <p class="text-iw-text-muted mt-2 max-w-2xl reveal" style="--reveal-delay: 0.08s">Doğru ürünü seçmenize yardımcı olacak güncel içerikler.</p>
    </div>
</section>

<div class="site-container py-10">
    <div class="grid gap-4 md:grid-cols-3" data-reveal-stagger=".guide-card">
        @forelse($guides as $guide)
        <a href="{{ route('guides.show', $guide->slug) }}" class="guide-card">
            <h2 class="guide-card__title">{{ $guide->title }}</h2>
            <p class="guide-card__excerpt">{{ $guide->excerpt }}</p>
            <span class="guide-card__link">Oku →</span>
        </a>
        @empty
        <p class="text-iw-text-muted">Henüz yayınlanmış rehber yok.</p>
        @endforelse
    </div>
</div>
@endsection

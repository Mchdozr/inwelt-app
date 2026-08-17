@extends('layouts.app')

@section('title', ($guide->seo_title ?: $guide->title).' | INWELT Rehber')
@section('description', $guide->seo_description ?: $guide->excerpt)
@section('image', $guide->og_image ?: $guide->cover_image ?: '')
@section('og_type', 'article')

@push('head')
<x-json-ld :data="\App\Support\Schema\SchemaBuilder::article($guide)" />
<x-json-ld :data="\App\Support\Schema\SchemaBuilder::breadcrumb([
    ['name' => 'Ana Sayfa', 'url' => route('home')],
    ['name' => 'Rehberler', 'url' => route('guides.index')],
    ['name' => $guide->title, 'url' => route('guides.show', $guide->slug)],
])" />
@if($guide->faq_items)
<x-json-ld :data="\App\Support\Schema\SchemaBuilder::faq($guide->faq_items)" />
@endif
@endpush

@section('content')
<article class="site-container py-10 max-w-3xl">
    <nav class="breadcrumb mb-6 reveal" aria-label="Konum">
        <a href="{{ route('home') }}">Ana Sayfa</a>
        <svg class="breadcrumb__sep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('guides.index') }}">Rehberler</a>
    </nav>

    <p class="guide-kicker reveal">Kısa cevap</p>
    <h1 class="text-3xl font-bold text-iw-text font-display reveal">{{ $guide->title }}</h1>
    <p class="text-iw-text-muted mt-3 leading-relaxed reveal" style="--reveal-delay: 0.08s">{{ $guide->excerpt }}</p>

    <div class="guide-meta reveal mt-4">
        @if($guide->author)
        <a href="{{ route('authors.show', $guide->author->slug) }}">{{ $guide->author->name }}</a>
        @endif
        @if($guide->published_at)
        <span>{{ $guide->published_at->format('d.m.Y') }}</span>
        @endif
        <span>Son güncelleme: {{ $guide->updated_at->format('d.m.Y') }}</span>
        @if($guide->reading_time_minutes)
        <span>{{ $guide->reading_time_minutes }} dk okuma</span>
        @endif
    </div>

    @if(! empty($guide->toc))
    <nav class="guide-toc reveal" aria-label="İçindekiler">
        <h2>İçindekiler</h2>
        <ol>
            @foreach($guide->toc as $item)
            <li><a href="#{{ $item['id'] }}">{{ $item['text'] }}</a></li>
            @endforeach
        </ol>
    </nav>
    @endif

    <div class="prose-iw mt-8 reveal">
        {!! $guide->body !!}
    </div>

    @if(! empty($guide->faq_items))
    <section class="mt-10">
        <h2 class="text-2xl font-bold font-display mb-4">SSS</h2>
        @foreach($guide->faq_items as $faq)
        <details class="faq-item">
            <summary class="faq-item__question">{{ $faq['question'] }}</summary>
            <p class="faq-item__answer">{{ $faq['answer'] }}</p>
        </details>
        @endforeach
    </section>
    @endif

    @if($relatedProducts->count())
    <section class="mt-12">
        <h2 class="text-xl font-bold font-display mb-4">İlgili ürünler</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach($relatedProducts as $product)
            <x-product-card :product="$product" compact />
            @endforeach
        </div>
    </section>
    @endif

    @if($guide->author)
    <aside class="author-card mt-12">
        <h2>Yazar</h2>
        <p class="font-semibold">{{ $guide->author->name }}</p>
        @if($guide->author->bio)<p class="text-sm text-iw-text-muted">{{ $guide->author->bio }}</p>@endif
        <a href="{{ route('authors.show', $guide->author->slug) }}">Profili gör</a>
    </aside>
    @endif
</article>
@endsection

@extends('layouts.app')

@section('title', $author->name.' | INWELT Yazar')
@section('description', $author->bio ?: ($author->name.' — INWELT içerik editörü.'))

@push('head')
<x-json-ld :data="array_merge(['@context' => 'https://schema.org'], \App\Support\Schema\SchemaBuilder::person($author))" />
@endpush

@section('content')
<section class="site-container py-12 max-w-3xl">
    <h1 class="text-3xl font-bold font-display">{{ $author->name }}</h1>
    @if($author->bio)
    <p class="mt-4 text-iw-text-muted leading-relaxed">{{ $author->bio }}</p>
    @endif
    @if($author->linkedin_url)
    <p class="mt-3"><a href="{{ $author->linkedin_url }}" rel="me noopener noreferrer" target="_blank">LinkedIn profili</a></p>
    @endif

    @if($guides->count())
    <h2 class="mt-10 text-xl font-bold font-display">Yazılar</h2>
    <div class="grid gap-4 mt-4">
        @foreach($guides as $guide)
        <a href="{{ route('guides.show', $guide->slug) }}" class="guide-card">
            <h3 class="guide-card__title">{{ $guide->title }}</h3>
            <p class="guide-card__excerpt">{{ $guide->excerpt }}</p>
        </a>
        @endforeach
    </div>
    @endif
</section>
@endsection

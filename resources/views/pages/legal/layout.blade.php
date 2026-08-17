@extends('layouts.app')

@section('title', $title)
@section('description', $description)

@section('content')
<article class="site-container py-12 max-w-3xl prose-iw">
    <h1>{{ $heading }}</h1>
    <p class="text-sm text-iw-text-muted">Son güncelleme: {{ now()->format('d.m.Y') }}</p>
    {!! $body !!}
</article>
@endsection

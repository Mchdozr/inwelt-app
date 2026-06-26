@extends('layouts.app')

@section('title', 'Sık Sorulan Sorular | INWELT')
@section('description', 'INWELT satın alma, Kacmasa yönlendirme, kargo ve iade hakkında sık sorulan sorular.')

@push('head')
@php
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($faqs)->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['answer'],
            ],
        ])->values()->all(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<section class="page-hero">
    <div class="relative site-container py-12">
        <h1 class="reveal">Sık Sorulan Sorular</h1>
        <p class="text-iw-text-muted mt-2 max-w-2xl reveal" style="--reveal-delay: 0.08s">INWELT marka vitrini ve satın alma kanalları hakkında merak ettikleriniz.</p>
    </div>
</section>

<div class="site-container py-10 max-w-3xl">
    <div class="space-y-4" data-reveal-stagger=".faq-item">
        @foreach($faqs as $faq)
        <details class="faq-item">
            <summary class="faq-item__question">{{ $faq['question'] }}</summary>
            <p class="faq-item__answer">{{ $faq['answer'] }}</p>
        </details>
        @endforeach
    </div>

    <div class="mt-10 p-6 rounded-2xl border border-iw-border bg-iw-panel reveal">
        <h2 class="text-lg font-semibold text-iw-text font-display">Başka sorunuz mu var?</h2>
        <p class="text-sm text-iw-text-muted mt-2">İletişim formu veya WhatsApp üzerinden bize ulaşın.</p>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('contact') }}" class="btn-primary">İletişime geç</a>
            <a href="{{ \App\Support\WhatsApp::url() }}" target="_blank" rel="noopener noreferrer" class="btn-outline" data-track-whatsapp="faq">WhatsApp</a>
        </div>
    </div>
</div>
@endsection

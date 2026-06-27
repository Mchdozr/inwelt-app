@extends('layouts.app')

@section('title', 'İletişim')
@section('description', 'INWELT ile iletişime geçin. Ürün bilgisi, sipariş ve stok durumu için formu doldurun.')

@section('content')

<section class="page-hero page-hero--center py-14 md:py-16">
    <div class="relative site-container">
        <h1 class="reveal">Bize ulaşın</h1>
        <p class="max-w-lg mx-auto reveal" style="--reveal-delay: 0.08s">Formu doldurun veya WhatsApp üzerinden yazın. Sipariş ve ürün sorularınızı en kısa sürede yanıtlıyoruz.</p>
    </div>
</section>

<div class="site-container pb-16 pt-10">
    <div class="grid lg:grid-cols-2 gap-10 items-start">
        <div class="contact-form-panel reveal">
            @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
            @endif

            <h2 class="text-xl font-bold text-iw-text mb-1 font-display">Mesaj Yazın Size Ulaşalım</h2>
            <p class="text-sm text-iw-text-muted mb-6">Zorunlu alanları doldurun, size geri dönelim.</p>

            <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
                @csrf

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-iw-text mb-1.5">Ad Soyad <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input @error('name') border-red-400 @enderror" placeholder="Ad Soyad" required>
                        @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-iw-text mb-1.5">E-posta <span class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input @error('email') border-red-400 @enderror" placeholder="ornek@mail.com" required>
                        @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-iw-text mb-1.5">Telefon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="input" placeholder="+90 5XX XXX XX XX">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-iw-text mb-1.5">Konu</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="input" placeholder="Ürün bilgisi, sipariş, stok…">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-iw-text mb-1.5">Mesaj <span class="text-red-400">*</span></label>
                    <textarea name="message" rows="5" class="input @error('message') border-red-400 @enderror resize-none" placeholder="Mesajınızı yazın…" required>{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-base">
                    Gönder
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </button>
            </form>
        </div>

        <div class="space-y-5 reveal" style="--reveal-delay: 0.1s">
            <div>
                <h2 class="text-2xl font-bold text-iw-text mb-2 font-display">İletişim kanalları</h2>
                <p class="text-iw-text-muted text-sm">Telefon hattımız yok; WhatsApp ve e-posta ile ulaşabilirsiniz.</p>
            </div>

            @include('partials.whatsapp-contact-card')

            @php
            $email = \App\Support\SiteContact::email();
            $address = \App\Support\SiteContact::address();
            @endphp

            @if($email)
            <div class="contact-info-card">
                <span class="contact-info-card__icon">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <div>
                    <div class="text-xs font-semibold text-iw-text-muted mb-0.5">E-posta</div>
                    <a href="mailto:{{ $email }}" class="font-semibold text-iw-text hover:text-iw-brand transition-colors no-underline">{{ $email }}</a>
                </div>
            </div>
            @endif

            @if($address)
            <div class="contact-info-card">
                <span class="contact-info-card__icon">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <div>
                    <div class="text-xs font-semibold text-iw-text-muted mb-0.5">Adres</div>
                    <div class="font-medium text-iw-text">{{ $address }}</div>
                </div>
            </div>
            @endif

            <div class="iw-panel p-5 rounded-2xl">
                <h3 class="font-semibold text-iw-text mb-3">Çalışma saatleri</h3>
                <div class="space-y-2 text-sm text-iw-text-muted">
                    <div class="flex justify-between gap-4"><span>Pazartesi - Cuma</span><span class="font-medium text-iw-text">08:30 - 18:30</span></div>
                    <div class="flex justify-between gap-4"><span>Cumartesi</span><span class="font-medium text-iw-text">09:00 - 14:00</span></div>
                    <div class="flex justify-between gap-4"><span>Pazar</span><span class="font-medium text-red-500">Kapalı</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

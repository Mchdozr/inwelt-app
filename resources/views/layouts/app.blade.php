<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.dataset.theme = stored || (prefersDark ? 'dark' : 'light');
        })();
    </script>
    <x-seo-meta
        :title="trim($__env->yieldContent('title') ?: 'INWELT')"
        :description="trim($__env->yieldContent('description') ?: 'INWELT — geniş ürün yelpazesi, uygun fiyatlar ve güvenilir alışveriş. Aradığınız her şey tek yerde.')"
        :image="trim($__env->yieldContent('image') ?: '') ?: null"
        :type="trim($__env->yieldContent('og_type') ?: 'website')"
    />
    @include('partials.analytics')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="page-shell text-iw-text font-sans antialiased">
@php $gtmId = config('analytics.gtm_container_id'); @endphp
@if($gtmId)
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif

    <header id="navbar" class="site-header fixed top-0 left-0 right-0 z-50 navbar-base">
        <div class="nav-promo-bar hidden sm:block border-b border-iw-border">
            <div class="site-container py-1.5 flex items-center justify-between gap-4 text-xs">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-iw-text-muted">
                    <span class="nav-promo-chip">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Hızlı kargo
                    </span>
                    <span class="nav-promo-chip">
                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Güvenli ödeme
                    </span>
                    <span class="nav-promo-chip hidden md:inline-flex">
                        <svg class="w-3.5 h-3.5 text-iw-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Güncel fırsatlar
                    </span>
                </div>
                <a href="{{ route('products.index', ['filtre' => 'deal']) }}" class="nav-promo-link shrink-0">Fırsatları gör →</a>
            </div>
        </div>

        <nav class="site-container h-16 lg:h-[4.5rem] flex items-center gap-3 lg:gap-4">
            <a href="{{ route('products.index') }}" class="brand-mark shrink-0" aria-label="INWELT Ana Sayfa">
                <img src="{{ asset('images/inwelt-logo.png') }}" alt="INWELT" class="brand-mark__img" width="180" height="44" decoding="async">
            </a>

            <form
                action="{{ route('products.index') }}"
                method="GET"
                class="nav-search hidden md:flex flex-1 max-w-sm lg:max-w-lg xl:max-w-xl 2xl:max-w-2xl mx-1 lg:mx-2"
                data-nav-search
                data-suggest-url="{{ route('search.suggest') }}"
                autocomplete="off"
            >
                <label for="navSearch" class="sr-only">Ürün ara</label>
                <input
                    id="navSearch"
                    type="search"
                    name="ara"
                    value="{{ request('ara') }}"
                    placeholder="Ürün, kategori ara…"
                    class="nav-search__input"
                    role="combobox"
                    aria-autocomplete="list"
                    aria-expanded="false"
                    aria-controls="navSearchDropdown"
                >
                <button type="submit" class="nav-search__btn" aria-label="Ara">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <div id="navSearchDropdown" class="nav-search-suggest" data-nav-search-dropdown role="listbox" aria-label="Arama önerileri" hidden></div>
            </form>

            <div class="hidden lg:flex items-center gap-0.5 shrink-0">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Ana Sayfa</a>

                <div class="relative group">
                    <button class="nav-link flex items-center gap-1 {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        Ürünler
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="mega-menu grid grid-cols-2 gap-1">
                        @foreach ($navCategories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="flex flex-col p-3 rounded-lg hover:bg-iw-surface transition-colors group/item">
                            <div class="font-medium text-sm text-iw-text">{{ $cat->name }}</div>
                            @if($cat->description)
                            <div class="text-xs text-iw-text-muted mt-0.5 line-clamp-1">{{ $cat->description }}</div>
                            @endif
                        </a>
                        @endforeach
                        <a href="{{ route('products.index') }}" class="col-span-2 mt-1 flex items-center justify-center gap-2 py-2.5 rounded-lg border border-iw-border text-sm font-medium hover:bg-iw-surface transition-colors">
                            Tüm Ürünleri Gör
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Hakkımızda</a>
                <a href="{{ route('guides.index') }}" class="nav-link {{ request()->routeIs('guides.*') ? 'active' : '' }}">Rehberler</a>
                <a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">SSS</a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">İletişim</a>
            </div>

            <div class="flex items-center gap-2 ml-auto shrink-0">
                <button type="button" id="themeToggle" class="theme-toggle hidden lg:flex" aria-label="Tema değiştir">
                    <svg id="themeIconSun" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg id="themeIconMoon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <a href="{{ route('products.index') }}" class="btn-primary text-sm hidden sm:inline-flex">Ürünleri Keşfet</a>
                <button type="button" id="themeToggleMobile" class="theme-toggle lg:hidden" aria-label="Tema değiştir">
                    <svg class="theme-icon-sun w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg class="theme-icon-moon w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <button id="mobileMenuBtn" class="p-2 rounded-lg text-iw-text-muted hover:text-iw-text transition-colors lg:hidden" aria-label="Menü">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </nav>

        <div class="nav-quick-rail hidden lg:block">
            <div class="site-container py-2.5">
                <div class="nav-quick-rail__inner">
                    <div class="nav-quick-rail__track no-scrollbar">
                        @foreach(\App\Support\ProductFilters::NAV_QUICK_FILTERS as [$label, $tone, $slug])
                        @php
                            $quickHref = $slug === '' ? route('products.index') : route('products.index', ['filtre' => $slug]);
                            $quickActive = $slug === ''
                                ? request()->routeIs('products.index') && ! request()->filled('filtre')
                                : request('filtre') === $slug;
                        @endphp
                        <a href="{{ $quickHref }}" class="nav-quick-pill nav-quick-pill--{{ $tone }}{{ $quickActive ? ' nav-quick-pill--active' : '' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                    @include('partials.social-links', ['variant' => 'quick-rail'])
                </div>
            </div>
        </div>

        <div id="mobileMenu" class="mobile-menu-panel hidden lg:hidden">
            <div class="site-container py-4 flex flex-col gap-3">
                <form
                    action="{{ route('products.index') }}"
                    method="GET"
                    class="nav-search nav-search--mobile"
                    data-nav-search
                    data-suggest-url="{{ route('search.suggest') }}"
                    autocomplete="off"
                >
                    <label for="navSearchMobile" class="sr-only">Ürün ara</label>
                    <input
                        id="navSearchMobile"
                        type="search"
                        name="ara"
                        placeholder="Ürün ara…"
                        class="nav-search__input"
                        role="combobox"
                        aria-autocomplete="list"
                        aria-expanded="false"
                        aria-controls="navSearchMobileDropdown"
                    >
                    <button type="submit" class="nav-search__btn" aria-label="Ara">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <div id="navSearchMobileDropdown" class="nav-search-suggest" data-nav-search-dropdown role="listbox" aria-label="Arama önerileri" hidden></div>
                </form>
                <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Ana Sayfa</a>
                <a href="{{ route('products.index') }}" class="mobile-nav-link">Ürünler</a>
                <a href="{{ route('about') }}" class="mobile-nav-link">Hakkımızda</a>
                <a href="{{ route('guides.index') }}" class="mobile-nav-link">Rehberler</a>
                <a href="{{ route('faq') }}" class="mobile-nav-link">SSS</a>
                <a href="{{ route('contact') }}" class="mobile-nav-link">İletişim</a>
                <div class="pt-2">
                    @include('partials.social-links', ['variant' => 'header'])
                </div>
                <a href="{{ route('products.index') }}" class="btn-primary text-center mt-1">Ürünleri Keşfet</a>
            </div>
        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="bg-iw-panel border-t border-iw-border mt-20">
        <div class="footer-trust">
            <div class="site-container footer-trust-grid">
                <div class="footer-trust-item">
                    <span class="footer-trust-item__icon"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                    <div><strong class="block text-sm font-semibold">Hızlı kargo</strong><span class="text-xs text-iw-text-muted">Aynı gün teslim</span></div>
                </div>
                <div class="footer-trust-item">
                    <span class="footer-trust-item__icon"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                    <div><strong class="block text-sm font-semibold">Güvenli ödeme</strong><span class="text-xs text-iw-text-muted">Güvenilir altyapı</span></div>
                </div>
                <div class="footer-trust-item">
                    <span class="footer-trust-item__icon"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                    <div><strong class="block text-sm font-semibold">Orijinal ürün</strong><span class="text-xs text-iw-text-muted">%100 garanti</span></div>
                </div>
                <div class="footer-trust-item">
                    <span class="footer-trust-item__icon"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></span>
                    <div><strong class="block text-sm font-semibold">7/24 destek</strong><span class="text-xs text-iw-text-muted">Hızlı yanıt</span></div>
                </div>
            </div>
        </div>
        <div class="site-container py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <a href="{{ route('products.index') }}" class="brand-mark brand-mark--footer" aria-label="INWELT Ana Sayfa">
                    <img src="{{ asset('images/inwelt-logo.png') }}" alt="INWELT" class="brand-mark__img brand-mark__img--footer" width="120" height="32" decoding="async">
                </a>
                <p class="mt-3 text-iw-text-muted text-sm leading-relaxed">
                    Geniş ürün yelpazesi, uygun fiyatlar ve güvenilir alışveriş deneyimi. Ev, hobi ve hediye ihtiyaçlarınız için doğru ürünü kolayca bulun. Sipariş sürecinden desteğe kadar yanınızdayız.
                </p>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-iw-text mb-4">Kategoriler</h4>
                <ul class="space-y-2 text-sm text-iw-text-muted">
                    @foreach($navCategories->take(6) as $cat)
                    <li><a href="{{ route('products.category', $cat->slug) }}" class="hover:text-iw-text transition-colors">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-iw-text mb-4">Bağlantılar</h4>
                <ul class="space-y-2 text-sm text-iw-text-muted">
                    <li><a href="{{ route('products.index') }}" class="hover:text-iw-text transition-colors">Ürünler</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-iw-text transition-colors">Hakkımızda</a></li>
                    <li><a href="{{ route('guides.index') }}" class="hover:text-iw-text transition-colors">Rehberler</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-iw-text transition-colors">SSS</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-iw-text transition-colors">İletişim</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-iw-text mb-4">İletişim</h4>
                <ul class="space-y-2 text-sm text-iw-text-muted">
                    @php
                        $phone = \App\Support\SiteContact::phone();
                        $email = \App\Support\SiteContact::email();
                        $address = \App\Models\Setting::get('site_address');
                    @endphp
                    <li><a href="{{ \App\Support\SiteContact::telHref($phone) }}" class="hover:text-iw-text transition-colors">{{ $phone }}</a></li>
                    @if($email)<li><a href="mailto:{{ $email }}" class="hover:text-iw-text transition-colors">{{ $email }}</a></li>@endif
                    @if($address)<li>{{ $address }}</li>@endif
                </ul>
                <h4 class="text-sm font-semibold text-iw-text mt-6 mb-3">Sosyal Medyalar</h4>
                @include('partials.social-links', ['variant' => 'footer'])
            </div>
        </div>
        <div class="border-t border-iw-border">
            <div class="site-container py-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-iw-text-muted">
                <span>© {{ date('Y') }} INWELT. Tüm hakları saklıdır.</span>
                <span>Aradığınız her şey, uygun fiyatla</span>
            </div>
        </div>
    </footer>

    @include('partials.whatsapp-float')

    <button type="button" id="backTop" class="back-top" aria-label="Yukarı çık">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
    </button>

    <script>
        const navbar = document.getElementById('navbar');
        const backTop = document.getElementById('backTop');
        let scrollTicking = false;
        function onScroll() {
            if (!scrollTicking) {
                scrollTicking = true;
                requestAnimationFrame(() => {
                    const y = window.scrollY;
                    navbar.classList.toggle('navbar-scrolled', y > 10);
                    backTop?.classList.toggle('is-visible', y > 480);
                    scrollTicking = false;
                });
            }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
        backTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        function updateThemeIcons(theme) {
            const isDark = theme === 'dark';
            document.getElementById('themeIconSun')?.classList.toggle('hidden', !isDark);
            document.getElementById('themeIconMoon')?.classList.toggle('hidden', isDark);
            document.querySelectorAll('.theme-icon-sun').forEach((el) => el.classList.toggle('hidden', !isDark));
            document.querySelectorAll('.theme-icon-moon').forEach((el) => el.classList.toggle('hidden', isDark));
        }

        function setTheme(theme) {
            document.documentElement.dataset.theme = theme;
            localStorage.setItem('theme', theme);
            updateThemeIcons(theme);
        }

        function toggleTheme() {
            const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            setTheme(next);
        }

        updateThemeIcons(document.documentElement.dataset.theme || 'light');
        document.getElementById('themeToggle')?.addEventListener('click', toggleTheme);
        document.getElementById('themeToggleMobile')?.addEventListener('click', toggleTheme);
    </script>
    @stack('scripts')
</body>
</html>

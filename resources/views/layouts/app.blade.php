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
        :description="trim($__env->yieldContent('description') ?: 'INWELT — akıllı cihazlar, RC oyuncaklar, müzik ve zeka oyunlarında hayatı kolaylaştıran teknoloji ürünleri.')"
        :image="trim($__env->yieldContent('image') ?: '') ?: null"
        :type="trim($__env->yieldContent('og_type') ?: 'website')"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-iw-deep text-iw-text font-inter antialiased">

    <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 navbar-base">
        <nav class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">

            <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline">
                <span class="text-xl font-extrabold tracking-tight text-iw-text">IN<span class="text-iw-accent">WELT</span></span>
            </a>

            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Ana Sayfa</a>

                <div class="relative group">
                    <button class="nav-link flex items-center gap-1 {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        Ürünler
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="mega-menu absolute top-full left-1/2 -translate-x-1/2 mt-2 w-[520px] bg-iw-panel border border-iw-border rounded-2xl shadow-2xl p-6 grid grid-cols-2 gap-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        @foreach ($navCategories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-iw-accent/[0.06] transition-colors group/item">
                            <span class="icon-chip !w-9 !h-9">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </span>
                            <div>
                                <div class="font-semibold text-sm text-iw-text group-hover/item:text-iw-accent transition-colors">{{ $cat->name }}</div>
                                @if($cat->description)
                                <div class="text-xs text-iw-text-muted mt-0.5 line-clamp-1">{{ $cat->description }}</div>
                                @endif
                            </div>
                        </a>
                        @endforeach
                        <a href="{{ route('products.index') }}" class="col-span-2 mt-2 flex items-center justify-center gap-2 py-2.5 rounded-xl border border-iw-border bg-iw-accent/10 text-iw-accent text-sm font-semibold hover:bg-iw-accent/15 transition-colors">
                            Tüm Ürünleri Gör
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Hakkımızda</a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">İletişim</a>
            </div>

            <div class="hidden lg:flex items-center gap-3">
                <button type="button" id="themeToggle" class="theme-toggle" aria-label="Tema değiştir">
                    <svg id="themeIconSun" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg id="themeIconMoon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <a href="{{ route('products.index') }}" class="btn-primary text-sm">Ürünleri Keşfet</a>
            </div>

            <div class="flex lg:hidden items-center gap-2">
                <button type="button" id="themeToggleMobile" class="theme-toggle" aria-label="Tema değiştir">
                    <svg class="theme-icon-sun w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg class="theme-icon-moon w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <button id="mobileMenuBtn" class="p-2 rounded-lg text-iw-text-muted hover:text-iw-text transition-colors" aria-label="Menü">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </nav>

        <div id="mobileMenu" class="hidden lg:hidden bg-iw-panel border-t border-iw-border">
            <div class="max-w-[1200px] mx-auto px-6 py-4 flex flex-col gap-2">
                <a href="{{ route('home') }}" class="mobile-nav-link">Ana Sayfa</a>
                <a href="{{ route('products.index') }}" class="mobile-nav-link">Ürünler</a>
                <a href="{{ route('about') }}" class="mobile-nav-link">Hakkımızda</a>
                <a href="{{ route('contact') }}" class="mobile-nav-link">İletişim</a>
                <a href="{{ route('products.index') }}" class="btn-primary text-center mt-2">Ürünleri Keşfet</a>
            </div>
        </div>
    </header>

    <main class="pt-16">
        @yield('content')
    </main>

    <footer class="bg-iw-graphite border-t border-iw-border mt-20">
        <div class="footer-trust">
            <div class="max-w-[1200px] mx-auto px-6 py-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach([
                    ['Hızlı Kargo', 'Aynı gün kargoya teslim'],
                    ['Güvenli Ödeme', 'Taksit ve güvenli altyapı'],
                    ['Kolay İletişim', 'Sorularınıza hızlı yanıt'],
                ] as [$title, $desc])
                <div class="footer-trust-item">
                    <span class="icon-chip !w-8 !h-8 !text-sm">✓</span>
                    <div>
                        <div class="font-semibold text-iw-text">{{ $title }}</div>
                        <div class="text-xs">{{ $desc }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="max-w-[1200px] mx-auto px-6 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <span class="text-2xl font-extrabold tracking-tight text-iw-text">IN<span class="text-iw-accent">WELT</span></span>
                <p class="mt-3 text-iw-text-muted text-sm leading-relaxed">
                    Akıllı cihazlardan eğlenceli oyuncaklara, hayatı kolaylaştıran ve renklendiren teknoloji ürünleri.
                </p>
                <div class="mt-4 flex gap-2">
                    <a href="#" class="social-btn" aria-label="Instagram">IG</a>
                    <a href="#" class="social-btn" aria-label="YouTube">YT</a>
                    <a href="#" class="social-btn" aria-label="X">X</a>
                </div>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-iw-text mb-4">Kategoriler</h4>
                <ul class="space-y-2 text-sm text-iw-text-muted">
                    @foreach($navCategories->take(6) as $cat)
                    <li><a href="{{ route('products.category', $cat->slug) }}" class="hover:text-iw-accent transition-colors">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-iw-text mb-4">Bağlantılar</h4>
                <ul class="space-y-2 text-sm text-iw-text-muted">
                    <li><a href="{{ route('products.index') }}" class="hover:text-iw-accent transition-colors">Ürünler</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-iw-accent transition-colors">Hakkımızda</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-iw-accent transition-colors">İletişim</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-iw-text mb-4">İletişim</h4>
                <ul class="space-y-2 text-sm text-iw-text-muted">
                    @php $phone = \App\Models\Setting::get('site_phone'); $email = \App\Models\Setting::get('site_email'); $address = \App\Models\Setting::get('site_address'); @endphp
                    @if($phone)<li><a href="tel:{{ $phone }}" class="hover:text-iw-accent transition-colors">{{ $phone }}</a></li>@endif
                    @if($email)<li><a href="mailto:{{ $email }}" class="hover:text-iw-accent transition-colors">{{ $email }}</a></li>@endif
                    @if($address)<li>{{ $address }}</li>@endif
                </ul>
            </div>
        </div>
        <div class="border-t border-iw-border">
            <div class="max-w-[1200px] mx-auto px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-iw-text-muted">
                <span>© {{ date('Y') }} INWELT. Tüm hakları saklıdır.</span>
                <span>Akıllı teknoloji &amp; tüketici ürünleri</span>
            </div>
        </div>
    </footer>

    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

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

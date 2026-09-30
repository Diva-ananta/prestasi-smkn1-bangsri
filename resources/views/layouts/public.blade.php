<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) document.documentElement.classList.add('dark');
        }());
    </script>
    <title>  @yield('title', 'SMK N 1 Bangsri')</title>
    <meta name="description" content="@yield('meta_description', 'Portal resmi informasi dan dokumentasi prestasi siswa SMK Negeri 1 Bangsri, Jepara.')">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smk.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-smk.png') }}">
    <meta property="og:title" content="@yield('title', 'SMK N 1 Bangsri')">
    <meta property="og:description" content="@yield('meta_description', 'Portal resmi informasi dan dokumentasi prestasi siswa SMK Negeri 1 Bangsri, Jepara.')">
    <meta property="og:image" content="{{ asset('images/logo-smk.png') }}">
    <meta property="og:type" content="website">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .page-title {
            color: #047857;
        }

        .page-subtitle {
            color: #64748b;
        }

        .dark .page-title {
            color: #34d399;
        }

        .dark .page-subtitle {
            color: #94a3b8;
        }

        .public-site .bg-clip-text {
            background-image: none !important;
            color: #047857 !important;
            -webkit-text-fill-color: currentColor;
        }

        .dark .public-site .bg-clip-text {
            color: #34d399 !important;
        }
    </style>
</head>
<body class="public-site bg-slate-100 text-slate-800 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">

<div aria-hidden="true" class="h-16"></div>
<nav
    x-data="{ open: false, scrolled: false, scrollProgress: 0, publicationOpen: false }"
    x-init="
        const updateScrollState = () => {
            scrollProgress = Math.min(window.scrollY / 180, 1);
            scrolled = window.innerWidth >= 768 && window.scrollY > 24;
        };
        updateScrollState();
        window.addEventListener('scroll', updateScrollState, { passive: true });
        window.addEventListener('resize', updateScrollState, { passive: true });
    "
    @keydown.escape.window="open = false; publicationOpen = false"
    :data-scrolled="scrolled"
    :class="scrolled
        ? 'rounded-[2rem] border border-white/70 bg-white/90 shadow-lg shadow-slate-900/10 dark:border-slate-700/80 dark:bg-slate-900/90'
        : 'rounded-none border-x-0 border-t-0 border-b border-slate-200/80 bg-white/90 shadow-none dark:border-slate-800 dark:bg-slate-900/90'"
    :style="{ top: scrolled ? '0.625rem' : '0', width: scrolled ? 'max-content' : '100%', maxWidth: scrolled ? 'calc(100vw - 1.5rem)' : '100%' }"
    class="navbar-sipres fixed left-1/2 top-0 z-50 w-full -translate-x-1/2 border backdrop-blur-xl transition-[top,width,max-width,border-radius,background-color,box-shadow,border-color] duration-500 ease-in-out"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div
            class="flex min-h-16 items-center py-2.5"
            :class="scrolled ? 'min-h-12 justify-between gap-2 py-1 md:justify-center md:gap-5' : 'justify-between gap-2'">
            {{-- LOGO DAN NAMA SIPRES --}}
            <a
                href="{{ route('home') }}"
                class="flex shrink-0 items-center gap-3 transition-[gap] duration-300":class="scrolled ? 'gap-0' : 'gap-3'">
                <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-xl" :class="scrolled ? 'h-10 w-10' : ''">
                    <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK Negeri 1 Bangsri" class="h-full w-full object-contain">
                </div>

                <div
                    x-show="!scrolled"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-x-2 opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="translate-x-2 opacity-0"
                    class="min-w-0">
                    <p class="truncate text-xs font-bold uppercase tracking-[0.16em] text-emerald-700 sm:text-sm dark:text-emerald-300">
                        SIPRES ESKASABA
                    </p>
                    <p class="truncate text-[10px] text-slate-500 sm:text-xs dark:text-slate-400">
                        Sistem Informasi Prestasi Siswa
                    </p>
                </div>
            </a>

            {{-- MENU DESKTOP --}}
            <div class="hidden items-center transition-[gap] duration-300 md:flex" :class="scrolled ? 'gap-0.5' : 'gap-1'">
                <a
                    href="{{ route('home') }}"
                    @class([
                        'nav-link rounded-full px-3 py-2 text-sm font-medium',
                        'is-active bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' => request()->routeIs('home'),
                        'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-emerald-300' => !request()->routeIs('home'),
                    ])
                >
                    Prestasi
                </a>

                <a
                    href="{{ route('public.prestasi.index') }}"
                    @class([
                        'nav-link rounded-full px-3 py-2 text-sm font-medium',
                        'is-active bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' => request()->routeIs('public.prestasi.*'),
                        'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-emerald-300' => !request()->routeIs('public.prestasi.*'),
                    ])
                >
                    Data Prestasi
                </a>

                <a
                    href="{{ route('public.siswa.search') }}"
                    @class([
                        'nav-link rounded-full px-3 py-2 text-sm font-medium',
                        'is-active bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' => request()->routeIs('public.siswa.*'),
                        'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-emerald-300' => !request()->routeIs('public.siswa.*'),
                    ])
                >
                    Siswa Berprestasi
                </a>

                {{-- DROPDOWN PUBLIKASI --}}
                <div class="relative">
                    <button
                        type="button"
                        @click="publicationOpen = !publicationOpen"
                        :aria-expanded="publicationOpen"
                        class="nav-link gap-2 rounded-full px-3 py-2 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-emerald-300"
                        :class="publicationOpen ? 'bg-emerald-50 text-emerald-700 dark:bg-slate-800 dark:text-emerald-300' : ''"
                    >
                        Publikasi
                        <i
                            class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300"
                            :class="publicationOpen ? 'rotate-180' : ''"
                        ></i>
                    </button>

                    <div
                        x-cloak
                        x-show="publicationOpen"
                        @click.outside="publicationOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="translate-y-2 scale-95 opacity-0"
                        x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                        x-transition:leave-end="translate-y-2 scale-95 opacity-0"
                        class="absolute right-0 top-full z-50 mt-3 w-48 origin-top-right rounded-2xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-900/10 dark:border-slate-700 dark:bg-slate-900"
                    >
                        <a
                            href="{{ route('public.artikel.index') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-emerald-300"
                        >
                            <i class="fa-regular fa-newspaper w-4"></i>
                            Artikel
                        </a>

                        <a
                            href="{{ route('public.galeri.index') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-emerald-300"
                        >
                            <i class="fa-regular fa-images w-4"></i>
                            Galeri
                        </a>
                    </div>
                </div>

                <a
                    href="{{ route('public.tentang') }}"@class([ 'nav-link rounded-full px-3 py-2 text-sm font-medium', 'is-active bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' => request()->routeIs('public.tentang'),
                        'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-emerald-300' => !request()->routeIs('public.tentang'),])>
                    Tentang
                </a>
            </div>

            {{-- TOMBOL KANAN --}}
            <div class="flex shrink-0 items-center transition-[gap] duration-300" :class="scrolled ? 'gap-1' : 'gap-2'">
                <x-dark-mode-toggle />
                <a
                    href="https://smkn1bangsri.sch.id/"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Website Sekolah"
                    title="Website Sekolah"
                    :class="scrolled ? 'px-2.5' : 'px-4'"
                    class="hidden items-center gap-2 rounded-full bg-emerald-700 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-700/20 transition-all duration-300 hover:-translate-y-0.5 hover:bg-emerald-800 hover:shadow-lg sm:inline-flex"
                >
                    <i class="fa-solid fa-globe"></i>
                    <span
                        x-show="!scrolled"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                    >Website Sekolah</span>
                </a>

                {{-- TOMBOL MENU MOBILE --}}
                <button
                    type="button"
                    @click="open = !open"
                    :aria-expanded="open"
                    aria-label="Buka atau tutup menu navigasi"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 transition duration-300 hover:bg-emerald-50 hover:text-emerald-700 md:hidden dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                >
                    <i
                        class="fa-solid text-lg"
                        :class="open ? 'fa-xmark rotate-90' : 'fa-bars'"
                    ></i>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MOBILE --}}
    <div
        class="grid md:hidden"
        style="transition: grid-template-rows 300ms ease, opacity 250ms ease;"
        :style="open
            ? 'grid-template-rows: 1fr; opacity: 1;'
            : 'grid-template-rows: 0fr; opacity: 0;'"
        :inert="!open"
    >
        <div class="overflow-hidden">
            <div class="space-y-1 border-t border-slate-200/80 px-4 py-3 dark:border-slate-800">
                <a
                    href="{{ route('home') }}"
                    @click="open = false"
                    @class([
                        'mobile-nav-link block rounded-xl px-4 py-3 text-sm font-medium',
                        'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' => request()->routeIs('home'),
                        'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' => !request()->routeIs('home'),
                    ])
                >
                    <i class="fa-solid fa-house mr-3 w-4"></i> Prestasi
                </a>

                <a
                    href="{{ route('public.prestasi.index') }}"
                    @click="open = false"
                    @class([
                        'mobile-nav-link block rounded-xl px-4 py-3 text-sm font-medium',
                        'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' => request()->routeIs('public.prestasi.*'),
                        'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' => !request()->routeIs('public.prestasi.*'),
                    ])
                >
                    <i class="fa-solid fa-trophy mr-3 w-4"></i> Data Prestasi
                </a>

                <a
                    href="{{ route('public.siswa.search') }}"
                    @click="open = false"
                    @class([
                        'mobile-nav-link block rounded-xl px-4 py-3 text-sm font-medium',
                        'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' => request()->routeIs('public.siswa.*'),
                        'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' => !request()->routeIs('public.siswa.*'),
                    ])
                >
                    <i class="fa-solid fa-user-graduate mr-3 w-4"></i> Siswa Berprestasi
                </a>

                {{-- PUBLIKASI MOBILE --}}
                <div>
                    <button
                        type="button"
                        @click="publicationOpen = !publicationOpen"
                        :aria-expanded="publicationOpen"
                        class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm font-medium text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-200 dark:hover:bg-slate-800" >
                        <span>
                            <i class="fa-solid fa-layer-group mr-3 w-4"></i> Publikasi
                        </span>
                        <i
                            class="fa-solid fa-chevron-down text-xs transition-transform duration-300"
                            :class="publicationOpen ? 'rotate-180' : ''"
                        ></i>
                    </button>

                    <div
                        x-cloak
                        x-show="publicationOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="-translate-y-2 opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="translate-y-0 opacity-100"
                        x-transition:leave-end="-translate-y-2 opacity-0"
                        class="mt-1 space-y-1 pl-5">
                        <a
                            href="{{ route('public.artikel.index') }}"
                            @click="open = false; publicationOpen = false"
                            class="mobile-nav-link block rounded-xl px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800">
                            <i class="fa-regular fa-newspaper mr-3 w-4"></i> Artikel
                        </a>

                        <a
                            href="{{ route('public.galeri.index') }}"
                            @click="open = false; publicationOpen = false"
                            class="mobile-nav-link block rounded-xl px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800">
                            <i class="fa-regular fa-images mr-3 w-4"></i> Galeri
                        </a>
                    </div>
                </div>

                <a
                    href="{{ route('public.tentang') }}"
                    @click="open = false"
                    @class([
                        'mobile-nav-link block rounded-xl px-4 py-3 text-sm font-medium',
                        'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' => request()->routeIs('public.tentang'),
                        'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' => !request()->routeIs('public.tentang'),
                    ])
                >
                    <i class="fa-solid fa-circle-info mr-3 w-4"></i> Tentang
                </a>

                <a
                    href="https://smkn1bangsri.sch.id/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-2 flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 sm:hidden"
                >
                    <i class="fa-solid fa-globe"></i>
                    Website Sekolah
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- CSS ANIMASI NAVBAR --}}
<style>
    [x-cloak] {
        display: none !important;
    }

    .navbar-sipres {
        transition:
            background-color .35s ease,
            box-shadow .35s ease,
            border-color .35s ease;
    }

    .navbar-sipres .nav-link {
        transition: padding .3s ease, color .3s ease, background-color .3s ease, transform .3s ease;
    }

    .navbar-sipres[data-scrolled="true"] .nav-link {
        padding-left: .625rem;
        padding-right: .625rem;
    }

    .navbar-sipres[data-scrolled="true"] .nav-link:not(.is-active) {
        color: #1e293b;
    }

    .navbar-sipres[data-scrolled="true"] .nav-link:hover {
        color: #065f46;
        background-color: #d1fae5;
    }

    .navbar-sipres[data-scrolled="true"] #darkModeToggle {
        color: #334155;
        background-color: rgb(255 255 255 / 55%);
    }

    .navbar-sipres[data-scrolled="true"] #darkModeToggle:hover {
        color: #065f46;
        background-color: #d1fae5;
    }

    .dark .navbar-sipres[data-scrolled="true"] .nav-link:not(.is-active) {
        color: #f1f5f9;
    }

    .dark .navbar-sipres[data-scrolled="true"] .nav-link:hover {
        color: #a7f3d0;
        background-color: rgb(6 78 59 / 75%);
    }

    .dark .navbar-sipres[data-scrolled="true"] #darkModeToggle {
        color: #e2e8f0;
        background-color: rgb(15 23 42 / 55%);
    }

    .dark .navbar-sipres[data-scrolled="true"] #darkModeToggle:hover {
        color: #a7f3d0;
        background-color: rgb(6 78 59 / 75%);
    }

    .nav-link {
        position: relative;
        transition:
            color .3s ease,
            background-color .3s ease,
            transform .3s ease;
    }

    .nav-link:hover {
        transform: translateY(-2px);
    }

    @media (prefers-reduced-motion: reduce) {
        .navbar-sipres,
        .nav-link,
        .mobile-nav-link {
            transition: none !important;
        }

        .nav-link:hover {
            transform: none;
        }
    }
</style>

        <main>
            @yield('content')
        </main>

        @include('components.footer')

        <button
            x-data="{ show: false }"
            x-show="show"
            x-transition.opacity
            x-init="window.addEventListener('scroll', () => show = window.scrollY > 400)"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 right-6 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-700 text-white shadow-xl shadow-emerald-900/30 transition hover:bg-emerald-800"
            aria-label="Kembali ke atas"
            style="display:none"
        >
            <x-icon name="arrow-up" />
        </button>

        @stack('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const revealItems = document.querySelectorAll('.reveal');
                if (!revealItems.length) return;

                if (!('IntersectionObserver' in window)) {
                    revealItems.forEach((item) => item.classList.add('is-visible'));
                    return;
                }

                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, { threshold: 0.14, rootMargin: '0px 0px -48px' });

                revealItems.forEach((item) => revealObserver.observe(item));
            });
        </script>
    </body>
</html>

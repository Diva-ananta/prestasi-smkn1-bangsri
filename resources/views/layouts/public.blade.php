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
    </style>
    </head>
    <body class="bg-slate-100 text-slate-800 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        <nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl transition-colors duration-300 dark:border-slate-800 dark:bg-slate-900/90">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex min-h-16 items-center justify-between gap-2 py-2 sm:py-2.5">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-1 shadow-sm">
                            <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK" class="h-full w-full object-contain">
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700 sm:text-sm sm:tracking-[0.2em] dark:text-emerald-300">SIPRES ESKASABA</p>
                            <p class="truncate text-xs text-slate-500 sm:text-xs dark:text-slate-400">Sistem Informasi Prestasi Siswa</p>
                        </div>
                    </a>

                    <div class="hidden items-center gap-1 rounded-full border border-slate-200 bg-slate-50/70 p-1.5 md:flex dark:border-slate-700 dark:bg-slate-800/60">
                        <a href="/" class="rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-emerald-300 {{ request()->routeIs('home') ? 'bg-emerald-100 text-emerald-800 shadow-sm dark:bg-emerald-950/70 dark:text-emerald-300' : '' }}">Beranda</a>
                        <a href="{{ route('public.prestasi.index') }}" class="rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-emerald-300 {{ request()->routeIs('public.prestasi.*') ? 'bg-emerald-100 text-emerald-800 shadow-sm dark:bg-emerald-950/70 dark:text-emerald-300' : '' }}">Prestasi</a>
                        <a href="{{ route('public.siswa.search') }}" class="rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-emerald-300 {{ request()->routeIs('public.siswa.*') ? 'bg-emerald-100 text-emerald-800 shadow-sm dark:bg-emerald-950/70 dark:text-emerald-300' : '' }}">Siswa Berprestasi</a>
                        <div x-data="{ publicationOpen: false }" class="relative">
                            <button type="button" @click="publicationOpen = !publicationOpen" class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-emerald-300 {{ request()->routeIs('public.artikel.*', 'public.galeri.*') ? 'bg-emerald-100 text-emerald-800 shadow-sm dark:bg-emerald-950/70 dark:text-emerald-300' : '' }}">Publikasi <x-icon name="chevron-down" class="text-xs" /></button>
                            <div x-show="publicationOpen" @click.outside="publicationOpen = false" x-transition class="absolute right-0 top-full z-50 mt-2 w-44 rounded-xl border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                                <a href="{{ route('public.artikel.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-emerald-300">Artikel</a>
                                <a href="{{ route('public.galeri.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-emerald-300">Galeri</a>
                            </div>
                        </div>
                        <a href="{{ route('public.tentang') }}" class="rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-emerald-300 {{ request()->routeIs('public.tentang') ? 'bg-emerald-100 text-emerald-800 shadow-sm dark:bg-emerald-950/70 dark:text-emerald-300' : '' }}">Tentang</a>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-dark-mode-toggle />
                        @auth
                            <a href="https://smkn1bangsri.sch.id/" target="_blank" rel="noopener noreferrer" class="hidden min-h-9 items-center rounded-full bg-emerald-700 px-3.5 py-1.5 text-sm font-semibold leading-5 text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-800 sm:inline-flex">
                                <x-icon name="globe" class="mr-2" /> Website Sekolah
                            </a>
                        @else
                            <a href="https://smkn1bangsri.sch.id/" target="_blank" rel="noopener noreferrer" class="hidden min-h-9 items-center rounded-full bg-emerald-700 px-3.5 py-1.5 text-sm font-semibold leading-5 text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-800 sm:inline-flex">
                                <x-icon name="globe" class="mr-2" /> Website Sekolah
                            </a>
                        @endauth

                        <button type="button" @click="open = !open" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-700 transition hover:bg-slate-100 md:hidden dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" aria-label="Buka menu">
                            <x-icon name="times" x-show="open" class="text-lg" />
                            <x-icon name="bars" x-show="!open" class="text-lg" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="relative md:hidden">
                <div x-show="open" x-transition.opacity class="absolute left-2 right-2 top-full z-50 mt-2 rounded-2xl border border-slate-200 bg-white/95 shadow-xl shadow-slate-900/10 dark:border-slate-800 dark:bg-slate-900/95">
                    <div class="mx-auto max-w-7xl space-y-2 px-4 py-4">
                        <a href="/" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : '' }}">Beranda</a>
                        <a href="{{ route('public.prestasi.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800 {{ request()->routeIs('public.prestasi.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : '' }}">Prestasi</a>
                        <a href="{{ route('public.siswa.search') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800 {{ request()->routeIs('public.siswa.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : '' }}">Siswa Berprestasi</a>
                        <div x-data="{ publicationOpen: {{ request()->routeIs('public.artikel.*', 'public.galeri.*') ? 'true' : 'false' }} }">
                            <button type="button" @click="publicationOpen = !publicationOpen" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 dark:text-slate-200 dark:hover:bg-slate-800 {{ request()->routeIs('public.artikel.*', 'public.galeri.*') ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' : '' }}">Publikasi <x-icon name="chevron-down" class="text-xs" /></button>
                            <div x-show="publicationOpen" x-transition class="mt-1 space-y-1 pl-3">
                                <a href="{{ route('public.artikel.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800 {{ request()->routeIs('public.artikel.*') ? 'bg-emerald-100 font-bold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' : '' }}">Artikel</a>
                                <a href="{{ route('public.galeri.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-800 {{ request()->routeIs('public.galeri.*') ? 'bg-emerald-100 font-bold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' : '' }}">Galeri</a>
                            </div>
                        </div>
                        <a href="{{ route('public.tentang') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800 {{ request()->routeIs('public.tentang') ? 'bg-emerald-100 font-bold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' : '' }}">Tentang</a>
                        @auth
                            <a href="https://smkn1bangsri.sch.id/" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white"><x-icon name="globe" /> Website Sekolah</a>
                        @else
                            <a href="https://smkn1bangsri.sch.id/" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white"><x-icon name="globe" /> Website Sekolah</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

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
@extends('layouts.public')

@section('title', 'Artikel & Berita Sekolah')

@section('content')
@php
    $featured = $artikels->first();
    $secondary = $artikels->skip(1)->take(2);
    $articles = $artikels->skip(3);
@endphp

<div class="min-h-screen bg-gradient-to-b from-slate-50 to-blue-50/30 dark:from-slate-950 dark:to-slate-900 animate-page-load">
    <!-- Header Section -->
    <header class="border-b-2 border-blue-100/50 bg-white/80 backdrop-blur-sm dark:border-slate-800 dark:bg-slate-900/80">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="animate-fade-in">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-blue-700 dark:text-blue-400">📰 Informasi Sekolah</p>
                <div class="mt-4 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                    <div>
                        <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">Artikel & Berita</h1>
                        <p class="mt-3 max-w-2xl text-lg text-slate-700 dark:text-slate-300">Cerita, kabar, pencapaian, dan update terbaru dari keluarga besar SMK Negeri 1 Bangsri.</p>
                    </div>
                    <div class="inline-flex shrink-0 items-center gap-3 rounded-xl bg-gradient-to-r from-blue-100 to-blue-50 px-5 py-3 dark:from-blue-950/50 dark:to-blue-950/30">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-white">
                            <i class="fas fa-newspaper text-sm"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400">Total Artikel</p>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $artikels->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @if($featured)
            <!-- Featured Article -->
            <section class="mb-14 animate-fade-in animate-delay-100">
                <a 
                    href="{{ route('public.artikel.show', $featured) }}" 
                    class="group grid overflow-hidden rounded-3xl border-2 border-blue-100 bg-white shadow-lg transition-smooth hover:-translate-y-2 hover:border-blue-300 hover:shadow-2xl dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-600 sm:grid-cols-[1.3fr_1fr] lg:grid-cols-[1.6fr_1fr]"
                >
                    <!-- Image -->
                    <div class="relative min-h-72 overflow-hidden bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-950/30 dark:to-blue-950/10 sm:min-h-80 lg:min-h-96">
                        <img 
                            src="{{ $featured->gambar ? asset('storage/' . $featured->gambar) : asset('images/logo-smk.png') }}" 
                            alt="{{ $featured->judul }}" 
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent opacity-0 transition group-hover:opacity-100"></div>
                    </div>

                    <!-- Content -->
                    <div class="flex flex-col justify-center p-7 sm:p-8 lg:p-10">
                        <div class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-100 px-4 py-1.5 text-xs font-bold text-blue-800 dark:bg-blue-950/50 dark:text-blue-300">
                            <i class="fas fa-star text-blue-600 dark:text-blue-400"></i> Berita Utama
                        </div>
                        
                        <h2 class="mt-5 text-3xl font-extrabold leading-tight text-slate-900 dark:text-white sm:text-4xl">
                            {{ $featured->judul }}
                        </h2>
                        
                        <p class="mt-4 line-clamp-3 text-base leading-7 text-slate-700 dark:text-slate-300">
                            {{ Str::limit($featured->isi, 200) }}
                        </p>

                        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
                            @if($featured->tanggal_publikasi)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-blue-600 dark:text-blue-400"></i>
                                    {{ $featured->tanggal_publikasi->translatedFormat('d F Y') }}
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-pen text-blue-600 dark:text-blue-400"></i>
                                Admin Sekolah
                            </div>
                        </div>

                        <div class="mt-7">
                            <span class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-100 to-blue-50 px-5 py-3 text-sm font-bold text-blue-700 transition group-hover:from-blue-200 group-hover:to-blue-100 dark:from-blue-950/50 dark:to-blue-950/30 dark:text-blue-400">
                                Baca Selengkapnya <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </section>

            <!-- Secondary Articles Grid -->
            @if($secondary->isNotEmpty())
                <div class="mb-14 grid gap-6 sm:grid-cols-2">
                    @foreach($secondary as $index => $artikel)
                        <a 
                            href="{{ route('public.artikel.show', $artikel) }}" 
                            class="group animate-fade-in overflow-hidden rounded-2xl border-2 border-slate-100 bg-white shadow-md transition-smooth hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-600"
                            style="animation-delay: {{ $index * 100 }}ms"
                        >
                            <div class="grid grid-cols-[1fr_1.2fr] overflow-hidden">
                                <!-- Image -->
                                <div class="relative min-h-56 overflow-hidden bg-blue-100 dark:bg-blue-950/30">
                                    <img 
                                        src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('images/logo-smk.png') }}" 
                                        alt="{{ $artikel->judul }}" 
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                    >
                                </div>
                                <!-- Content -->
                                <div class="flex flex-col justify-center p-5 lg:p-6">
                                    <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                                        {{ $artikel->tanggal_publikasi?->translatedFormat('d M Y') ?? 'Berita' }}
                                    </p>
                                    <h3 class="mt-2 line-clamp-3 text-lg font-bold leading-snug text-slate-900 dark:text-white">
                                        {{ $artikel->judul }}
                                    </h3>
                                    <span class="mt-4 inline-flex w-fit items-center gap-2 text-xs font-bold text-blue-700 dark:text-blue-400">
                                        Baca <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif

        <!-- All Articles Section -->
        @if($articles->isNotEmpty() || $secondary->isNotEmpty())
            <section class="animate-fade-in animate-delay-300">
                <div class="mb-10 flex flex-col justify-between gap-4 border-b-2 border-blue-100 pb-6 dark:border-slate-800 sm:flex-row sm:items-center">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-700 dark:text-blue-400">📚 Koleksi Berita</p>
                        <h2 class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white">Berita Terkini</h2>
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($artikels->skip(1) as $index => $artikel)
                        <article 
                            class="group animate-fade-in overflow-hidden rounded-2xl border-2 border-slate-100 bg-white shadow-sm transition-smooth hover:-translate-y-2 hover:border-blue-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-600"
                            style="animation-delay: {{ $index * 100 }}ms"
                        >
                            <!-- Image -->
                            <a 
                                href="{{ route('public.artikel.show', $artikel) }}" 
                                class="relative block h-56 overflow-hidden bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-950/30 dark:to-blue-950/10"
                            >
                                <img 
                                    src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('images/logo-smk.png') }}" 
                                    alt="{{ $artikel->judul }}" 
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent opacity-0 transition group-hover:opacity-100"></div>
                            </a>

                            <!-- Content -->
                            <div class="p-5 lg:p-6">
                                <div class="inline-flex items-center gap-2">
                                    <i class="fas fa-calendar text-blue-600 dark:text-blue-400 text-xs"></i>
                                    <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                                        {{ $artikel->tanggal_publikasi?->translatedFormat('d M Y') ?? 'Berita' }}
                                    </p>
                                </div>

                                <h3 class="mt-3 line-clamp-2 min-h-14 text-lg font-bold leading-6 text-slate-900 dark:text-white">
                                    <a href="{{ route('public.artikel.show', $artikel) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                                        {{ $artikel->judul }}
                                    </a>
                                </h3>

                                <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600 dark:text-slate-400">
                                    {{ Str::limit($artikel->isi, 120) }}
                                </p>

                                <a 
                                    href="{{ route('public.artikel.show', $artikel) }}" 
                                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-50 px-4 py-2 text-xs font-bold text-blue-700 transition hover:bg-blue-100 dark:bg-blue-950/30 dark:text-blue-400 dark:hover:bg-blue-950/60"
                                >
                                    Baca Selengkapnya <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @elseif(!$featured)
            <div class="animate-fade-in rounded-2xl border-2 border-dashed border-slate-300 bg-white p-16 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <i class="fas fa-inbox text-5xl text-slate-400 dark:text-slate-600 mb-4"></i>
                <p class="text-lg font-semibold text-slate-600 dark:text-slate-400">Belum ada artikel yang dipublikasikan</p>
                <p class="mt-2 text-slate-500 dark:text-slate-500">Kembali lagi untuk membaca update terbaru dari sekolah kami.</p>
            </div>
        @endif

        <!-- Pagination -->
        @if($artikels->hasPages())
            <div class="mt-12">{{ $artikels->links() }}</div>
        @endif
    </main>
</div>
@endsection

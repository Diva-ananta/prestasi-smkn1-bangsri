@extends('layouts.public')

@section('title', $artikel->judul)

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-blue-50/30 dark:from-slate-950 dark:to-slate-900 animate-page-load">
    <div class="mx-auto max-w-5xl px-4 py-9 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-10 animate-fade-in text-sm">
            <div class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 transition hover:text-blue-700 dark:hover:text-blue-400">
                    <i class="fas fa-home text-xs"></i> Beranda
                </a>
                <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                <a href="{{ route('public.artikel.index') }}" class="inline-flex items-center gap-2 transition hover:text-blue-700 dark:hover:text-blue-400">
                    <i class="fas fa-newspaper text-xs"></i> Artikel
                </a>
                <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                <span class="font-semibold text-slate-900 dark:text-white">Berita</span>
            </div>
        </nav>

        <div class="grid gap-7 lg:grid-cols-[1fr_280px]">
            <!-- Main Article -->
            <article class="animate-fade-in animate-delay-100 min-w-0">
                <!-- Header -->
                <header class="mb-10 border-b-2 border-blue-100 pb-8 dark:border-slate-800">
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-1.5 text-xs font-bold text-blue-800 dark:bg-blue-950/50 dark:text-blue-300">
                        <i class="fas fa-newspaper"></i> Artikel
                    </div>

                    <h1 class="mt-4 max-w-4xl text-5xl font-extrabold leading-tight text-slate-900 dark:text-white sm:text-6xl">
                        {{ $artikel->judul }}
                    </h1>

                    <!-- Meta Information -->
                    <div class="mt-6 flex flex-wrap items-center gap-6 text-sm text-slate-600 dark:text-slate-400">
                        <div class="flex items-center gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-950/50 dark:text-blue-400">
                                <i class="fas fa-user-pen text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Penulis</p>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $artikel->penulis ?? 'Redaksi Sekolah' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-950/50 dark:text-purple-400">
                                <i class="fas fa-calendar text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tanggal</p>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $artikel->tanggal_publikasi?->translatedFormat('d F Y') ?? 'Tidak ditentukan' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                                <i class="fas fa-tag text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Kategori</p>
                                <p class="font-semibold text-slate-900 dark:text-white">Berita Sekolah</p>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Featured Image -->
                @if($artikel->gambar)
                    <figure class="mb-10 animate-fade-in animate-delay-200 overflow-hidden rounded-3xl shadow-xl">
                        <img 
                            src="{{ asset('storage/' . $artikel->gambar) }}" 
                            alt="{{ $artikel->judul }}" 
                            class="max-h-[600px] w-full object-cover transition-transform duration-700 hover:scale-105"
                        >
                    </figure>
                @endif

                <!-- Article Content -->
                <div class="prose prose-slate max-w-none text-lg leading-8 text-slate-700 dark:prose-dark dark:text-slate-300">
                    <div class="rounded-2xl border-2 border-blue-100 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                        {!! nl2br(e($artikel->isi)) !!}
                    </div>
                </div>

                @if($artikel->video_url)
                    @php
                        $videoHost = strtolower((string) parse_url($artikel->video_url, PHP_URL_HOST));
                        $videoHost = preg_replace('/^www\./', '', $videoHost);
                        $videoPlatform = str_contains($videoHost, 'youtube') ? 'YouTube' : (str_contains($videoHost, 'instagram') ? 'Instagram' : 'TikTok');
                        $videoIcon = $videoPlatform === 'YouTube' ? 'fa-youtube' : ($videoPlatform === 'Instagram' ? 'fa-instagram' : 'fa-tiktok');
                    @endphp
                    <div class="mt-8 rounded-2xl border-2 border-red-100 bg-red-50 p-5 dark:border-red-900/40 dark:bg-red-950/20">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-red-700 dark:text-red-300"><i class="fab {{ $videoIcon }} mr-2"></i>Video {{ $videoPlatform }}</p>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Tonton video terkait artikel ini di platform publik.</p>
                        <a href="{{ $artikel->video_url }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700">
                            <i class="fas fa-play"></i> Buka Video
                        </a>
                    </div>
                @endif

                <!-- Related Achievement -->
                @if($artikel->prestasi)
                    <div class="mt-10 animate-fade-in animate-delay-300">
                        <a 
                            href="{{ route('public.prestasi.show', $artikel->prestasi->public_token) }}" 
                            class="group flex gap-6 overflow-hidden rounded-2xl border-2 border-emerald-100 bg-white p-6 shadow-md transition-smooth hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg dark:border-emerald-900/40 dark:bg-slate-900 dark:hover:border-emerald-600 sm:p-8"
                        >
                            <div class="h-32 w-40 shrink-0 overflow-hidden rounded-lg">
                                <img 
                                    src="{{ $artikel->prestasi->foto ? asset('storage/' . $artikel->prestasi->foto) : asset('images/logo-smk.png') }}" 
                                    alt="{{ $artikel->prestasi->nama_lomba }}" 
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                >
                            </div>
                            <div class="flex flex-1 flex-col justify-center min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                                    <i class="fas fa-star mr-1.5"></i>Prestasi Terkait
                                </p>
                                <h3 class="mt-2 text-xl font-bold text-slate-900 dark:text-white line-clamp-2">
                                    {{ $artikel->prestasi->nama_lomba }}
                                </h3>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                                        <i class="fas fa-medal"></i>{{ $artikel->prestasi->hasil }}
                                    </span>
                                    <span class="ml-2 inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-800 dark:bg-blue-950/60 dark:text-blue-300">
                                        <i class="fas fa-layer-group"></i>{{ $artikel->prestasi->tingkat ?? 'Umum' }}
                                    </span>
                                </p>
                                <p class="mt-3 inline-flex items-center gap-2 text-sm font-bold text-emerald-700 transition group-hover:translate-x-1 dark:text-emerald-400">
                                    Lihat Prestasi <i class="fas fa-arrow-right"></i>
                                </p>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- Back Button -->
                <div class="mt-12 border-t-2 border-blue-100 pt-8 dark:border-slate-800">
                    <a 
                        href="{{ route('public.artikel.index') }}" 
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-5 py-3 text-sm font-bold text-blue-700 transition hover:bg-blue-200 dark:bg-blue-950/50 dark:text-blue-400 dark:hover:bg-blue-950/70"
                    >
                        <i class="fas fa-arrow-left"></i> Kembali ke Semua Artikel
                    </a>
                </div>
            </article>

            <!-- Sidebar - Related Articles -->
            @if($artikelTerkait->isNotEmpty())
                <aside class="animate-fade-in animate-delay-200">
                    <div class="sticky top-24 rounded-2xl border-2 border-blue-100 bg-white p-6 shadow-lg dark:border-slate-800 dark:bg-slate-900">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                                <i class="fas fa-bookmark mr-2 text-blue-600 dark:text-blue-400"></i>Bacaan Lainnya
                            </h3>
                            <a 
                                href="{{ route('public.artikel.index') }}" 
                                class="text-xs font-bold text-blue-700 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                Semua
                            </a>
                        </div>

                        <div class="space-y-4 border-t-2 border-blue-100 pt-6 dark:border-slate-800">
                            @foreach($artikelTerkait as $index => $related)
                                <a 
                                    href="{{ route('public.artikel.show', $related) }}" 
                                    class="group block animate-fade-in transition-smooth hover:translate-x-1"
                                    style="animation-delay: {{ $index * 100 }}ms"
                                >
                                    @if($related->gambar)
                                        <div class="mb-3 h-24 overflow-hidden rounded-lg">
                                            <img 
                                                src="{{ asset('storage/' . $related->gambar) }}" 
                                                alt="{{ $related->judul }}" 
                                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            >
                                        </div>
                                    @endif
                                    <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-900 group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-400">
                                        {{ $related->judul }}
                                    </h4>
                                    <p class="mt-2 line-clamp-1 text-xs text-slate-500 dark:text-slate-400">
                                        <i class="fas fa-calendar text-blue-600 dark:text-blue-400 mr-1"></i>
                                        {{ $related->tanggal_publikasi?->translatedFormat('d M Y') ?? 'Tidak ditentukan' }}
                                    </p>
                                </a>
                            @endforeach
                        </div>

                        <!-- CTA -->
                        <a 
                            href="{{ route('public.artikel.index') }}" 
                            class="mt-6 block w-full rounded-lg bg-gradient-to-r from-blue-100 to-blue-50 px-4 py-3 text-center text-sm font-bold text-blue-700 transition hover:from-blue-200 hover:to-blue-100 dark:from-blue-950/50 dark:to-blue-950/30 dark:text-blue-400 dark:hover:from-blue-950/70 dark:hover:to-blue-950/50"
                        >
                            Lihat Semua Artikel
                        </a>
                    </div>
                </aside>
            @endif
        </div>
    </div>
</div>
@endsection

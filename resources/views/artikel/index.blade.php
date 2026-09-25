@extends('layouts.public')

@section('title', 'Artikel & Berita Sekolah')

@section('content')
<div class="min-h-screen bg-[#f6faf7] dark:bg-slate-950">
    <section class="border-b border-emerald-100 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 pb-10 pt-12 text-center sm:px-6 lg:px-8 lg:pb-14 lg:pt-16">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-700 dark:text-emerald-400">Informasi sekolah</p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-5xl">
                <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent dark:from-emerald-400 dark:to-blue-400">Artikel &amp; Berita</span>
            </h1>
            <p class="page-subtitle mx-auto mt-4 max-w-xl text-sm leading-7 sm:text-base">Cerita, kabar, dan informasi terbaru dari keluarga besar SMK Negeri 1 Bangsri.</p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        @if($artikels->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($artikels as $index => $artikel)
                    <a href="{{ route('public.artikel.show', $artikel) }}" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900" style="animation-delay: {{ $index * 80 }}ms">
                        <div class="relative aspect-[4/3] overflow-hidden bg-emerald-50 dark:bg-emerald-950">
                            <img src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('images/logo-smk.png') }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/45 via-transparent to-transparent opacity-0 transition group-hover:opacity-100"></div>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">{{ $artikel->tanggal_publikasi?->translatedFormat('d M Y') ?? 'Berita' }}</p>
                            <h2 class="mt-2 line-clamp-2 text-lg font-bold leading-snug text-slate-900 dark:text-white">{{ $artikel->judul }}</h2>
                            <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ Str::limit($artikel->isi, 120) }}</p>
                            <span class="mt-5 inline-flex items-center gap-2 text-xs font-bold text-emerald-700 dark:text-emerald-400">Baca selengkapnya <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center dark:border-slate-700 dark:bg-slate-900">
                <i class="fas fa-inbox text-4xl text-slate-400 dark:text-slate-600"></i>
                <p class="mt-4 font-semibold text-slate-600 dark:text-slate-400">Belum ada artikel yang dipublikasikan.</p>
                <p class="mt-2 text-sm text-slate-500">Kembali lagi untuk membaca informasi terbaru dari sekolah kami.</p>
            </div>
        @endif

        @if($artikels->hasPages())
            <div class="mt-10">{{ $artikels->links() }}</div>
        @endif
    </section>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Detail Artikel')

@section('content')
<div class="page-shell">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-400">Manajemen konten</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">Detail Artikel</h1>
        </div>
        <div class="flex gap-2">
            <a data-ajax-page href="{{ route('admin.artikel.edit', $artikel) }}" title="Edit artikel" aria-label="Edit artikel" class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700 transition hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-300"><i class="fas fa-pen"></i></a>
            <a href="{{ route('admin.artikel.index') }}" title="Kembali ke daftar artikel" aria-label="Kembali ke daftar artikel" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"><i class="fas fa-arrow-left"></i></a>
        </div>
    </div>

    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <header class="border-b border-slate-200 px-6 py-7 dark:border-slate-800 md:px-10 md:py-9">
            <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-wider">
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300"><i class="fas fa-newspaper mr-1.5"></i>Artikel</span>
                <span class="rounded-full px-3 py-1 {{ $artikel->status === 'Publish' ? 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' }}">{{ $artikel->status }}</span>
            </div>
            <h2 class="mt-4 max-w-4xl break-words text-3xl font-black leading-tight text-slate-900 dark:text-white md:text-4xl">{{ $artikel->judul }}</h2>
            <div class="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500 dark:text-slate-400">
                <span><i class="fas fa-user mr-2 text-emerald-600"></i>{{ $artikel->penulis ?? 'Redaksi Sekolah' }}</span>
                <span><i class="fas fa-calendar-alt mr-2 text-emerald-600"></i>{{ $artikel->tanggal_publikasi?->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</span>
            </div>
        </header>

        <div class="grid lg:grid-cols-[minmax(0,1fr)_260px]">
            <div class="p-6 md:p-10">
                @if($artikel->gambar)
                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="mb-8 max-h-[28rem] w-full rounded-2xl object-cover shadow-sm">
                @else
                    <div class="mb-8 flex h-48 items-center justify-center rounded-2xl border border-dashed border-emerald-200 bg-emerald-50 text-emerald-300 dark:border-emerald-900/50 dark:bg-emerald-950/20 dark:text-emerald-800"><i class="fas fa-image text-5xl"></i></div>
                @endif
                <div class="prose prose-slate max-w-none text-base leading-8 dark:prose-invert">
                    {!! nl2br(e($artikel->isi)) !!}
                </div>
            </div>

            <aside class="border-t border-slate-200 bg-slate-50/70 p-6 dark:border-slate-800 dark:bg-slate-950/30 lg:border-l lg:border-t-0">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Informasi terkait</p>
                @if($artikel->prestasi)
                    <div class="mt-4 rounded-xl border border-emerald-200 bg-white p-4 dark:border-emerald-900/50 dark:bg-slate-900">
                        <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400"><i class="fas fa-trophy mr-1.5"></i>Prestasi terkait</p>
                        <p class="mt-2 font-bold text-slate-800 dark:text-white">{{ $artikel->prestasi->nama_lomba }}</p>
                        <a data-ajax-page href="{{ route('admin.prestasi.edit', $artikel->prestasi) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-emerald-700 hover:underline dark:text-emerald-400">Edit prestasi <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                @else
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-slate-400">Belum ada prestasi yang dihubungkan dengan artikel ini.</p>
                @endif
            </aside>
        </div>
    </article>
</div>
@endsection
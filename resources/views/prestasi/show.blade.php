@extends('layouts.public')

@section('title', $prestasi->nama_lomba . ' - SMK N 1 Bangsri')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($prestasi->keterangan ?? $prestasi->nama_lomba), 155))

@section('content')
@php
    $ketuaTim = $prestasi->detailPrestasi->first()?->siswa;
    $pesertaIndividu = $prestasi->jenis_peserta === 'Individu' ? $ketuaTim?->nama : null;
    $ekstrakurikulerUrl = $prestasi->nama_tim ? config('app.ekstrakurikuler.' . $prestasi->nama_tim) : null;
    $tahun = $prestasi->tanggal_mulai ? \Carbon\Carbon::parse($prestasi->tanggal_mulai)->format('Y') : $prestasi->created_at->format('Y');
    $metaItems = [
        ['icon' => 'building-columns', 'label' => 'Penyelenggara', 'value' => $prestasi->penyelenggara],
        ['icon' => $prestasi->jenis_peserta === 'Tim' ? 'people-group' : 'user', 'label' => $prestasi->jenis_peserta === 'Tim' ? 'Nama tim' : 'Nama siswa', 'value' => $prestasi->jenis_peserta === 'Tim' ? $prestasi->nama_tim : $pesertaIndividu, 'url' => $prestasi->jenis_peserta === 'Tim' ? $ekstrakurikulerUrl : null],
        ['icon' => 'location-dot', 'label' => 'Lokasi', 'value' => $prestasi->lokasi],
        ['icon' => 'shapes', 'label' => 'Kategori lomba', 'value' => $prestasi->kategori],
        ['icon' => 'shapes', 'label' => 'Bidang lomba', 'value' => $prestasi->bidang_lomba],
        ['icon' => 'star', 'label' => 'Kategori juara', 'value' => $prestasi->kategori_juara],
    ];
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-slate-950">

    {{-- ===================== BREADCRUMB ===================== --}}
    <div class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <nav class="mx-auto flex max-w-6xl items-center gap-2 px-4 py-3.5 text-xs font-semibold text-slate-400 sm:px-6 lg:px-8" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-emerald-700 dark:hover:text-emerald-400">Beranda</a>
            <x-icon name="chevron-right" class="text-xs" />
            <a href="{{ route('public.prestasi.index') }}" class="transition hover:text-emerald-700 dark:hover:text-emerald-400">Prestasi</a>
            <x-icon name="chevron-right" class="text-xs" />
            <span class="truncate text-slate-600 dark:text-slate-300">{{ $prestasi->nama_lomba }}</span>
        </nav>
    </div>

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-clip border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-emerald-100 blur-3xl dark:bg-emerald-900/20"></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-teal-100 blur-3xl dark:bg-teal-900/10"></div>

        <div class="relative mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(280px,0.8fr)_minmax(0,1.2fr)] lg:items-start">

                {{-- FOTO --}}
                <div class="relative aspect-[4/4] overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-lg dark:border-slate-700 dark:bg-slate-900">
                    @if($prestasi->foto)
                        <img
                            src="{{ asset('storage/' . $prestasi->foto) }}"
                            alt="{{ $prestasi->nama_lomba }}"
                            class="h-full w-full object-cover" >
                    @else
                        <div class="flex h-full items-center justify-center">
                            <i class="fas fa-trophy text-6xl text-emerald-300"></i>
                        </div>
                    @endif

                    {{-- Badge juara --}}
                    <div class="absolute left-4 top-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-extrabold text-amber-700 shadow-lg backdrop-blur">
                            <i class="fas fa-medal"></i>
                            JUARA {{ $prestasi->hasil }}
                        </span>
                    </div>
                </div>

                {{-- INFORMASI --}}
                <div class="min-w-0">

                    {{-- Label --}}
                    <div class="mb-2 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.16em] text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Detail Prestasi · {{ $tahun }}
                    </div>

                    {{-- JUDUL --}}
                    <h1 class="text-3xl font-black leading-tight tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                        {{ $prestasi->nama_lomba }}
                    </h1>

                    {{-- DESKRIPSI --}}
                    <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Diraih oleh
                        @if($prestasi->jenis_peserta === 'Tim' && $prestasi->nama_tim)
                            @if($ekstrakurikulerUrl)
                                <a href="{{ $ekstrakurikulerUrl }}" target="_blank" rel="noopener noreferrer" class="font-bold text-emerald-700 hover:underline dark:text-emerald-400">{{ $prestasi->nama_tim }}</a>
                            @else
                                <span class="font-bold text-emerald-700 dark:text-emerald-400">{{ $prestasi->nama_tim }}</span>
                            @endif
                        @elseif($ketuaTim)
                            <a href="{{ route('public.siswa.show', ['token' => $ketuaTim->public_token]) }}" class="font-bold text-emerald-700 hover:underline dark:text-emerald-400">{{ $ketuaTim->nama }}</a>
                        @else
                            <span class="font-bold text-emerald-700 dark:text-emerald-400">-</span>
                        @endif
                        @if($prestasi->jenis_peserta !== 'Tim' && $ketuaTim?->jurusan)
                            dari jurusan {{ $ketuaTim->jurusan }}.
                        @endif
                    </p>

                    {{-- BADGE --}}
                    <div class="mt-3 flex flex-wrap gap-2">
                        @if($prestasi->tingkat)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                <i class="fas fa-layer-group"></i>
                                {{ $prestasi->tingkat }}
                            </span>
                        @endif
                        @if($prestasi->kategori)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                <i class="fas fa-tag"></i>
                                {{ $prestasi->kategori }}
                            </span>
                        @endif
                    </div>

                    {{-- DETAIL GRID --}}
                    <div class="mt-6 grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                        {{-- PERAIH --}}
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                            <div class="flex items-start gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                    <i class="fas fa-user text-xs"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400">
                                        Nama Peraih
                                    </p>
                                    <p class="mt-0.5 truncate text-xs font-bold text-slate-800 dark:text-slate-100">
                                        @if($ketuaTim)
                                            <a href="{{ route('public.siswa.show', ['token' => $ketuaTim->public_token]) }}" class="text-emerald-700 hover:underline dark:text-emerald-400">{{ $ketuaTim->nama }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                            <div class="flex items-start gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400">
                                    <i class="fas fa-calendar-alt text-xs"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400">
                                        Tanggal Pelaksanaan
                                    </p>
                                    <p class="mt-0.5 text-xs font-bold text-slate-800 dark:text-slate-100">
                                        @if($prestasi->tanggal_mulai)
                                            {{ $prestasi->tanggal_mulai->translatedFormat('d F Y') }}
                                            @if($prestasi->tanggal_selesai && $prestasi->tanggal_selesai->ne($prestasi->tanggal_mulai))
                                                <span class="font-medium text-slate-500 dark:text-slate-400">s/d {{ $prestasi->tanggal_selesai->translatedFormat('d F Y') }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- PENYELENGGARA --}}
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                            <div class="flex items-start gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                    <i class="fas fa-university text-xs"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400">
                                        Penyelenggara
                                    </p>
                                    <p class="mt-0.5 truncate text-xs font-bold text-slate-800 dark:text-slate-100">
                                        {{ $prestasi->penyelenggara ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- KATEGORI --}}
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                            <div class="flex items-start gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                    <i class="fas fa-shapes text-xs"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400">
                                        Kategori
                                    </p>
                                    <p class="mt-0.5 truncate text-xs font-bold text-slate-800 dark:text-slate-100">
                                        {{ $prestasi->kategori ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== KONTEN ===================== --}}
    <main class="mx-auto max-w-6xl px-4 py-9 sm:px-6 lg:px-8">
        <div class="grid items-stretch gap-6 md:grid-cols-[minmax(0,1fr)_240px]">

            <div class="animate-fade-in space-y-6">

                {{-- Anggota tim --}}
                @if($prestasi->jenis_peserta === 'Tim' && $prestasi->detailPrestasi->isNotEmpty())
                    <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="flex items-center gap-2 text-base font-bold text-slate-900 dark:text-white">
                            <x-icon name="user-group" class="text-emerald-600" /> Daftar Anggota Tim
                        </h2>
                        <div class="mt-4 grid gap-2 sm:grid-cols-3">
                            @foreach($prestasi->detailPrestasi as $detail)
                                @if($detail->siswa)
                                    <a href="{{ route('public.siswa.show', ['token' => $detail->siswa->public_token]) }}" class="flex min-w-0 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm font-semibold text-slate-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/30 dark:hover:text-emerald-300">
                                        <x-icon name="user" class="shrink-0 text-xs text-slate-400" />
                                        <span class="min-w-0 break-words">{{ $detail->siswa->nama }}</span>
                                        @if($detail->peran)
                                            <span class="ml-auto shrink-0 text-xs font-normal text-slate-400">{{ $detail->peran }}</span>
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Deskripsi --}}
                @if($prestasi->keterangan)
                    <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="flex items-center gap-2 text-base font-bold text-slate-900 dark:text-white">
                            <x-icon name="align-left" class="text-emerald-600" /> Deskripsi
                        </h2>
                        <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600 dark:text-slate-300">
                            {{ $prestasi->keterangan }}
                        </p>
                    </div>
                @endif

                {{-- Artikel terkait --}}
                @if($prestasi->artikel->isNotEmpty())
                    <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="flex items-center gap-2 text-base font-bold text-slate-900 dark:text-white">
                            <x-icon name="newspaper" class="text-emerald-600" /> Artikel Terkait
                        </h2>
                        <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($prestasi->artikel as $artikel)
                                <a href="{{ route('public.artikel.show', $artikel) }}" class="group flex gap-4 py-4 first:pt-0 last:pb-0">
                                    <img src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('images/logo-smk.png') }}" alt="{{ $artikel->judul }}" class="h-16 w-20 shrink-0 rounded-xl object-cover transition duration-300 group-hover:scale-105">
                                    <div class="min-w-0">
                                        <p class="line-clamp-1 text-sm font-bold text-slate-800 transition group-hover:text-emerald-700 dark:text-white dark:group-hover:text-emerald-400">{{ $artikel->judul }}</p>
                                        <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500 dark:text-slate-400">{{ Str::limit(strip_tags($artikel->isi), 110) }}</p>
                                        <p class="mt-1.5 text-xs font-semibold text-slate-400"><x-icon name="calendar-alt" class="mr-1" />{{ $artikel->tanggal_publikasi?->format('d/m/Y') ?? '-' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Prestasi terkait --}}
                @if($prestasiTerkait->isNotEmpty())
                    <div>
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <h2 class="flex items-center gap-2 text-base font-bold text-slate-900 dark:text-white">
                                <x-icon name="layer-group" class="text-emerald-600" /> Prestasi Terkait
                            </h2>
                            <a href="{{ route('public.prestasi.index') }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Lihat semua</a>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-3">
                            @foreach($prestasiTerkait as $related)
                                <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                                    <a href="{{ route('public.prestasi.show', $related->public_token) }}" class="block h-28 overflow-hidden bg-gradient-to-br from-slate-100 to-emerald-50 dark:from-slate-800 dark:to-slate-900">
                                        <img src="{{ $related->foto ? asset('storage/' . $related->foto) : asset('images/logo-smk.png') }}" alt="{{ $related->nama_lomba }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                    </a>
                                    <div class="p-4">
                                        <span class="text-xs font-bold uppercase tracking-wide text-emerald-700 dark:text-emerald-400">{{ $related->hasil }} &middot; {{ $related->tingkat ?? 'Umum' }}</span>
                                        <h3 class="mt-1.5 line-clamp-2 text-sm font-bold leading-5 text-slate-800 dark:text-white">
                                            <a href="{{ route('public.prestasi.show', $related->public_token) }}">{{ $related->nama_lomba }}</a>
                                        </h3>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Sidebar --}}
            <aside class="h-fit space-y-4 md:sticky md:top-24 md:self-start">
                <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-400">Ringkasan</p>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-3 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">Hasil</dt>
                            <dd class="text-right font-bold text-slate-900 dark:text-white">{{ $prestasi->hasil }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-3 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">Tingkat</dt>
                            <dd class="text-right font-bold text-slate-900 dark:text-white">{{ $prestasi->tingkat ?? 'Umum' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-3 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">Kategori</dt>
                            <dd class="text-right font-bold text-slate-900 dark:text-white">{{ $prestasi->kategori ?? 'Umum' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-500 dark:text-slate-400">Tahun</dt>
                            <dd class="text-right font-bold text-slate-900 dark:text-white">{{ $tahun }}</dd>
                        </div>
                    </dl>
                </div>

                <a href="{{ route('public.prestasi.index') }}" class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    <x-icon name="arrow-left" />
                    Kembali ke Daftar Prestasi
                </a>
            </aside>
        </div>
    </main>
</div>
@endsection
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
        ['icon' => 'fa-building-columns', 'label' => 'Penyelenggara', 'value' => $prestasi->penyelenggara],
        ['icon' => $prestasi->jenis_peserta === 'Tim' ? 'fa-people-group' : 'fa-user', 'label' => $prestasi->jenis_peserta === 'Tim' ? 'Nama tim' : 'Nama siswa', 'value' => $prestasi->jenis_peserta === 'Tim' ? $prestasi->nama_tim : $pesertaIndividu, 'url' => $prestasi->jenis_peserta === 'Tim' ? $ekstrakurikulerUrl : null],
        ['icon' => 'fa-location-dot', 'label' => 'Lokasi', 'value' => $prestasi->lokasi],
        ['icon' => 'fa-shapes', 'label' => 'Kategori lomba', 'value' => $prestasi->kategori],
        ['icon' => 'fa-shapes', 'label' => 'Bidang lomba', 'value' => $prestasi->bidang_lomba],
        ['icon' => 'fa-star', 'label' => 'Kategori juara', 'value' => $prestasi->kategori_juara],
    ];
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-slate-950">

    {{-- ===================== BREADCRUMB ===================== --}}
    <div class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <nav class="mx-auto flex max-w-6xl items-center gap-2 px-4 py-3.5 text-xs font-semibold text-slate-400 sm:px-6 lg:px-8" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-emerald-700 dark:hover:text-emerald-400">Beranda</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="{{ route('public.prestasi.index') }}" class="transition hover:text-emerald-700 dark:hover:text-emerald-400">Prestasi</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="truncate text-slate-600 dark:text-slate-300">{{ $prestasi->nama_lomba }}</span>
        </nav>
    </div>

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-clip border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-emerald-100 blur-3xl dark:bg-emerald-900/20"></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-teal-100 blur-3xl dark:bg-teal-900/10"></div>

        <div class="relative mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            <div class="grid gap-7 lg:grid-cols-[380px_1fr] lg:items-start">

                {{-- Foto --}}
                <div class="animate-fade-in">
                    <div class="relative h-72 overflow-hidden rounded-[26px] border border-slate-200 bg-gradient-to-br from-slate-100 to-emerald-50 shadow-lg dark:border-slate-800 dark:from-slate-800 dark:to-slate-900 sm:h-80 lg:h-96">
                        @if($prestasi->foto)
                            <img src="{{ asset('storage/' . $prestasi->foto) }}" alt="{{ $prestasi->nama_lomba }}" class="h-full w-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>
                        @else
                            <div class="flex h-full w-full items-center justify-center text-emerald-300 dark:text-emerald-800">
                                <i class="fas fa-trophy text-7xl"></i>
                            </div>
                        @endif

                        <div class="absolute left-4 top-4 flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-black uppercase tracking-wide text-amber-700 shadow-lg backdrop-blur dark:bg-slate-900/90 dark:text-amber-400">
                            <i class="fas fa-medal"></i>
                            {{ $prestasi->hasil }}
                        </div>
                    </div>

                    @if($prestasi->sertifikat)
                        <a href="{{ asset('storage/' . $prestasi->sertifikat) }}" target="_blank" rel="noopener" class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300 dark:hover:bg-emerald-950/50">
                            <i class="fas fa-file-certificate"></i>
                            Lihat sertifikat
                        </a>
                    @endif
                </div>

                {{-- Judul & ringkasan --}}
                <div class="animate-fade-in flex flex-col" style="animation-delay: 80ms">
                    <div class="mb-4 inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>
                        Detail prestasi &middot; {{ $tahun }}
                    </div>

                    <h1 class="max-w-4xl break-words text-3xl font-black leading-tight tracking-tight md:text-4xl">
                        <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent dark:from-emerald-400 dark:to-blue-400">{{ $prestasi->nama_lomba }}</span>
                    </h1>

                    <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        @if($prestasi->tanggal_mulai)
                            <div class="flex items-start gap-2.5">
                                <i class="fas fa-calendar-days mt-1 shrink-0 text-emerald-600 dark:text-emerald-400"></i>
                                <div>
                                    <span class="font-semibold text-slate-500 dark:text-slate-400">Tanggal pelaksanaan:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ \Carbon\Carbon::parse($prestasi->tanggal_mulai)->translatedFormat('d F Y') }}</span>
                                    @if($prestasi->tanggal_selesai && $prestasi->tanggal_selesai != $prestasi->tanggal_mulai)
                                        <span class="font-medium text-slate-500 dark:text-slate-400">s/d {{ \Carbon\Carbon::parse($prestasi->tanggal_selesai)->translatedFormat('d F Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($prestasi->nama_tim)
                            <div class="flex items-start gap-2.5">
                                <i class="fas fa-people-group mt-1 shrink-0 text-emerald-600 dark:text-emerald-400"></i>
                                <span class="font-semibold text-slate-500 dark:text-slate-400">Ekstrakurikuler:</span>
                                @if($ekstrakurikulerUrl)<a href="{{ $ekstrakurikulerUrl }}" target="_blank" rel="noopener noreferrer" class="font-bold text-emerald-700 hover:underline dark:text-emerald-400">{{ $prestasi->nama_tim }}</a>@else<span class="font-bold text-slate-800 dark:text-slate-100">{{ $prestasi->nama_tim }}</span>@endif
                            </div>
                        @elseif($pesertaIndividu)
                            <div class="flex items-start gap-2.5">
                                <i class="fas fa-user mt-1 shrink-0 text-emerald-600 dark:text-emerald-400"></i>
                                <span class="font-semibold text-slate-500 dark:text-slate-400">Nama siswa:</span>
                                <a href="{{ route('public.siswa.profile', ['nama' => \Illuminate\Support\Str::slug($ketuaTim->nama)]) }}" class="font-bold text-emerald-700 hover:underline dark:text-emerald-400">{{ $pesertaIndividu }}</a>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.1em] text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                            <i class="fas fa-layer-group"></i>{{ $prestasi->tingkat ?? 'Umum' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.1em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                            <i class="fas fa-tag"></i>{{ $prestasi->kategori ?? 'Umum' }}
                        </span>
                    </div>

                    <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-600 dark:text-slate-300">
                        @if($prestasi->jenis_peserta == 'Tim' && $prestasi->nama_tim)
                            Diraih oleh tim @if($ekstrakurikulerUrl)<a href="{{ $ekstrakurikulerUrl }}" target="_blank" rel="noopener noreferrer" class="font-bold text-emerald-700 hover:underline dark:text-emerald-400">{{ $prestasi->nama_tim }}</a>@else<span class="font-bold text-slate-900 dark:text-white">{{ $prestasi->nama_tim }}</span>@endif
                            @if($prestasi->detailPrestasi->isNotEmpty())
                                beranggotakan {{ $prestasi->detailPrestasi->count() }} siswa.
                            @endif
                        @elseif($ketuaTim)
                            Diraih oleh <a href="{{ route('public.siswa.profile', ['nama' => \Illuminate\Support\Str::slug($ketuaTim->nama)]) }}" class="font-bold text-emerald-700 hover:underline dark:text-emerald-400">{{ $ketuaTim->nama }}</a>
                            @if($ketuaTim->jurusan) dari jurusan {{ $ketuaTim->jurusan }} @endif.
                        @else
                            Prestasi siswa SMK N 1 Bangsri.
                        @endif
                    </p>

                    {{-- Info cepat --}}
                    <div class="mt-8 grid gap-3 sm:grid-cols-2">
                        @foreach($metaItems as $meta)
                            @if($meta['value'])
                                <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900/60">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                        <i class="fas {{ $meta['icon'] }} text-sm"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $meta['label'] }}</p>
                                        <p class="mt-0.5 truncate text-sm font-bold text-slate-800 dark:text-slate-100" title="{{ $meta['value'] }}">
                                            @if($meta['url'] ?? null)
                                                <a href="{{ $meta['url'] }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline dark:text-emerald-400">{{ $meta['value'] }}</a>
                                            @else
                                                {{ $meta['value'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== KONTEN ===================== --}}
    <main class="mx-auto max-w-6xl px-4 py-9 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[1fr_280px]">

            <div class="animate-fade-in space-y-6">

                {{-- Anggota tim --}}
                @if($prestasi->jenis_peserta === 'Tim' && $prestasi->detailPrestasi->isNotEmpty())
                    <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="flex items-center gap-2 text-base font-bold text-slate-900 dark:text-white">
                            <i class="fas fa-user-group text-emerald-600"></i> Daftar Anggota Tim
                        </h2>
                        <div class="mt-4 grid gap-2 sm:grid-cols-3">
                            @foreach($prestasi->detailPrestasi as $detail)
                                @if($detail->siswa)
                                    <a href="{{ route('public.siswa.profile', ['nama' => \Illuminate\Support\Str::slug($detail->siswa->nama)]) }}" class="flex min-w-0 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm font-semibold text-slate-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/30 dark:hover:text-emerald-300">
                                        <i class="fas fa-user shrink-0 text-[11px] text-slate-400"></i>
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
                            <i class="fas fa-align-left text-emerald-600"></i> Deskripsi
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
                            <i class="fas fa-newspaper text-emerald-600"></i> Artikel Terkait
                        </h2>
                        <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($prestasi->artikel as $artikel)
                                <a href="{{ route('public.artikel.show', $artikel) }}" class="group flex gap-4 py-4 first:pt-0 last:pb-0">
                                    <img src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('images/logo-smk.png') }}" alt="{{ $artikel->judul }}" class="h-16 w-20 shrink-0 rounded-xl object-cover transition duration-300 group-hover:scale-105">
                                    <div class="min-w-0">
                                        <p class="line-clamp-1 text-sm font-bold text-slate-800 transition group-hover:text-emerald-700 dark:text-white dark:group-hover:text-emerald-400">{{ $artikel->judul }}</p>
                                        <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500 dark:text-slate-400">{{ Str::limit(strip_tags($artikel->isi), 110) }}</p>
                                        <p class="mt-1.5 text-[11px] font-semibold text-slate-400"><i class="fas fa-calendar-alt mr-1"></i>{{ $artikel->tanggal_publikasi?->format('d/m/Y') ?? '-' }}</p>
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
                                <i class="fas fa-layer-group text-emerald-600"></i> Prestasi Terkait
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
                                        <span class="text-[10px] font-bold uppercase tracking-wide text-emerald-700 dark:text-emerald-400">{{ $related->hasil }} &middot; {{ $related->tingkat ?? 'Umum' }}</span>
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
            <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
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
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar Prestasi
                </a>
            </aside>
        </div>
    </main>
</div>
@endsection
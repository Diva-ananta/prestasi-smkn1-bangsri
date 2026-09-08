@extends('layouts.public')

@section('title', 'Profil Siswa - ' . $siswa->nama)

@section('content')
@php
    $initials = collect(explode(' ', trim($siswa->nama)))->filter()->map(fn ($word) => strtoupper(substr($word, 0, 1)))->take(2)->join('');
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-slate-950">
    <section class="relative overflow-hidden border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-24 -top-28 h-72 w-72 rounded-full bg-slate-100 dark:bg-slate-800/60"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-12 text-center sm:px-6 lg:px-8 lg:py-14">
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white sm:text-4xl">Profil Siswa</h1>
            <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $siswa->nama }}</p>
        </div>
    </section>

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
        <div class="grid items-start gap-6 lg:grid-cols-[235px_minmax(0,1fr)]">
            <aside class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:sticky lg:top-24">
                <div class="relative h-20 bg-emerald-700">
                    <div class="absolute -bottom-9 left-1/2 flex h-[74px] w-[74px] -translate-x-1/2 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-slate-100 text-xl font-bold text-emerald-700 shadow-md dark:border-slate-900 dark:bg-slate-800 dark:text-emerald-300">
                        @if($siswa->foto)
                            <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama }}" class="h-full w-full object-cover">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                </div>
                <div class="px-5 pb-5 pt-12 text-center">
                    <h2 class="break-words text-base font-bold text-slate-900 dark:text-white">{{ $siswa->nama }}</h2>
                    <p class="mt-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400">NIS: {{ $siswa->nis }}</p>
                    <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>
                    <dl class="grid grid-cols-2 gap-x-3 gap-y-4 text-left">
                        <div><dt class="text-[10px] text-slate-400">Kelas</dt><dd class="mt-1 break-words text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->kelas ?? '-' }}</dd></div>
                        <div><dt class="text-[10px] text-slate-400">Jurusan</dt><dd class="mt-1 break-words text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->jurusan ?? '-' }}</dd></div>
                        <div><dt class="text-[10px] text-slate-400">Angkatan</dt><dd class="mt-1 text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->angkatan ?? '-' }}</dd></div>
                        <div><dt class="text-[10px] text-slate-400">Status</dt><dd class="mt-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400">{{ $siswa->status ?? '-' }}</dd></div>
                    </dl>
                </div>
            </aside>

            <section>
                <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3 dark:border-slate-700">
                    <h2 class="flex items-center gap-2 text-base font-bold text-slate-800 dark:text-white"><i class="fas fa-trophy text-emerald-700 dark:text-emerald-400"></i> Riwayat Prestasi</h2>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">{{ $prestasi->count() }} prestasi</span>
                </div>

                <div class="space-y-3 lg:max-h-[calc(100vh-10rem)] lg:overflow-y-auto lg:overscroll-contain lg:pr-1">
                    @forelse($prestasi as $index => $item)
                        @php $year = $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('Y') : optional($item->created_at)->format('Y'); @endphp
                        <article class="animate-fade-in rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900" style="animation-delay: {{ $index * 80 }}ms">
                            <div class="flex gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-emerald-700 dark:bg-slate-800 dark:text-emerald-400"><i class="fas fa-award"></i></div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0">
                                            <div class="mb-1.5 flex flex-wrap gap-1.5">
                                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $item->hasil }}</span>
                                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">{{ $item->tingkat ?? 'Umum' }}</span>
                                            </div>
                                            <h3 class="break-words text-sm font-bold text-slate-900 dark:text-white">{{ $item->nama_lomba }}</h3>
                                            @if($item->nama_tim)
                                                @php $ekstrakurikulerUrl = config('app.ekstrakurikuler.' . $item->nama_tim); @endphp
                                                <p class="mt-1 text-xs font-semibold text-blue-700 dark:text-blue-400"><i class="fas fa-users mr-1"></i>@if($ekstrakurikulerUrl)<a href="{{ $ekstrakurikulerUrl }}" target="_blank" rel="noopener noreferrer" class="hover:underline">{{ $item->nama_tim }}</a>@else{{ $item->nama_tim }}@endif</p>
                                            @endif
                                            <p class="mt-1.5 text-xs leading-5 text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($item->keterangan ?? 'Prestasi siswa pada kompetisi ' . ($item->tingkat ?? 'umum') . '.', 150) }}</p>
                                        </div>
                                        <div class="shrink-0 text-left sm:text-right"><p class="text-[10px] text-slate-400">Tahun</p><p class="text-sm font-bold text-slate-800 dark:text-white">{{ $year }}</p></div>
                                    </div>
                                    <a href="{{ route('public.prestasi.show', $item->public_token) }}" class="mt-2 inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Lihat detail <i class="fas fa-arrow-right text-[10px]"></i></a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center dark:border-slate-700 dark:bg-slate-900"><i class="fas fa-inbox text-3xl text-slate-300 dark:text-slate-600"></i><p class="mt-3 text-sm font-semibold text-slate-600 dark:text-slate-400">Belum ada prestasi</p></div>
                    @endforelse
                </div>

                @if($artikel->isNotEmpty())
                    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="flex items-center gap-2 text-base font-bold text-slate-900 dark:text-white"><i class="fas fa-newspaper text-emerald-600"></i> Artikel Terkait</h2>
                        <div class="mt-3 divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($artikel as $itemArtikel)
                                <a href="{{ route('public.artikel.show', $itemArtikel) }}" class="flex gap-3 py-3 first:pt-0 last:pb-0">
                                    <img src="{{ $itemArtikel->gambar ? asset('storage/' . $itemArtikel->gambar) : asset('images/logo-smk.png') }}" alt="{{ $itemArtikel->judul }}" class="h-14 w-16 shrink-0 rounded-lg object-cover">
                                    <span class="line-clamp-2 text-sm font-bold text-slate-800 dark:text-white">{{ $itemArtikel->judul }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </main>
</div>
@endsection

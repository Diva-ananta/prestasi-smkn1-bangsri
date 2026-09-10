@extends('layouts.public')

@section('title', 'Prestasi Siswa | SMK N 1 Bangsri')

@section('content')
@php
    $chartJurusanLabels = $analitikJurusan->pluck('jurusan')->map(fn ($jurusan) => $jurusan ?: 'Belum diisi')->all();
    $chartJurusanData = $analitikJurusan->pluck('total')->all();
    $chartTahunLabels = $analitikTahun->pluck('tahun')->all();
    $chartTahunData = $analitikTahun->pluck('total')->all();
    $chartTingkatLabels = $analitikTingkat->pluck('tingkat')->map(fn ($tingkat) => $tingkat ?: 'Belum diisi')->all();
    $chartTingkatData = $analitikTingkat->pluck('total')->all();
    $chartData = [
        'tahunLabels' => $chartTahunLabels,
        'tahunData' => $chartTahunData,
        'jurusanLabels' => $chartJurusanLabels,
        'jurusanData' => $chartJurusanData,
        'tingkatLabels' => $chartTingkatLabels,
        'tingkatData' => $chartTingkatData,
    ];
@endphp
<div class="min-h-screen bg-slate-50 dark:bg-slate-950">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-emerald-100 blur-3xl dark:bg-emerald-900/20"></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-teal-100 blur-3xl dark:bg-teal-900/10"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8 lg:py-11">
            <div class="grid items-stretch gap-6 lg:grid-cols-2">
                <div class="animate-fade-in flex min-w-0 flex-col">
                    <div class="mb-5 inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>
                        Portal prestasi sekolah
                    </div>
                    <h1 class="text-4xl font-black leading-[1.1] tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                        Prestasi siswa <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent dark:from-emerald-400 dark:to-blue-400">SMK N 1 Bangsri</span>
                    </h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-300">
                        Jelajahi pencapaian siswa, lihat distribusi prestasi per jurusan, dan temukan data terbaik dari sekolah dalam satu tampilan yang profesional dan mudah dibaca.
                    </p>
                    <form id="prestasi-search" action="{{ route('public.prestasi.index') }}" method="GET" class="mt-5 flex flex-col gap-2 sm:flex-row">
                        <label for="hero-achievement-search" class="sr-only">Cari prestasi</label>
                        <div class="relative flex-1"><i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input id="hero-achievement-search" name="q" value="{{ request('q') }}" type="search" placeholder="Cari nama lomba, siswa, atau NIS" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pl-11 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white"></div>
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700 sm:w-auto"><i class="fas fa-search"></i>Cari prestasi</button>
                    </form>
                    <a href="{{ route('public.siswa.search') }}" class="mt-3 inline-flex max-w-full items-center gap-2 self-start rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300 dark:hover:bg-emerald-950/50"><i class="fas fa-user-graduate"></i><span class="truncate">Lihat siswa berprestasi</span><i class="fas fa-arrow-right shrink-0 text-[10px]"></i></a>
                </div>

                <div id="prestasi-analytics" x-data="{ activeChart: 'tahun', selectChart(name) { this.activeChart = name; requestAnimationFrame(() => window.dispatchEvent(new Event('resize'))); } }" class="min-w-0 rounded-[20px] border border-slate-200 bg-white p-4 shadow-lg dark:border-slate-800 dark:bg-slate-900 sm:rounded-[24px] sm:p-5">
                    <script id="prestasi-chart-data" type="application/json">{!! json_encode($chartData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
                    <div class="flex min-h-10 items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Analitik prestasi</p>
                            <h2 class="mt-1 break-words text-base font-bold text-slate-900 dark:text-white sm:text-lg" x-text="{ tahun: 'Prestasi berdasarkan tahun', jurusan: 'Prestasi berdasarkan jurusan', tahunTrend: 'Prestasi berdasarkan tahun', tingkat: 'Prestasi berdasarkan tingkat' }[activeChart]"></h2>
                        </div>
                        <span class="hidden text-xs text-slate-400 sm:inline">Grafik batang</span>
                    </div>
                    <div class="mt-4 h-48 sm:h-56"><canvas id="tahunChart" x-show="activeChart === 'tahun'"></canvas><canvas id="jurusanChart" x-show="activeChart === 'jurusan'"></canvas><canvas id="tahunTrendChart" x-show="activeChart === 'tahunTrend'"></canvas><canvas id="tingkatChart" x-show="activeChart === 'tingkat'"></canvas></div>
                    <div class="mt-4 grid grid-cols-2 gap-2 border-t border-slate-100 pt-4 dark:border-slate-800 sm:grid-cols-4">
                        <button type="button" @click="selectChart('tahun')" :class="activeChart === 'tahun' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-lg px-2 py-2 text-xs font-bold transition">Tahun</button>
                        <button type="button" @click="selectChart('jurusan')" :class="activeChart === 'jurusan' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-lg px-2 py-2 text-xs font-bold transition">Jurusan</button>
                        <button type="button" @click="selectChart('tahunTrend')" :class="activeChart === 'tahunTrend' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-lg px-2 py-2 text-xs font-bold transition">Tren Tahun</button>
                        <button type="button" @click="selectChart('tingkat')" :class="activeChart === 'tingkat' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-lg px-2 py-2 text-xs font-bold transition">Tingkat</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== KARTU OVERVIEW ===================== --}}
    <section id="prestasi-overview" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @php
                $overview = [
                    ['label' => 'Total Prestasi', 'value' => number_format($analitikRingkasan['total']), 'icon' => 'fa-trophy', 'classes' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
                    ['label' => 'Juara / Finalis', 'value' => number_format($analitikRingkasan['juara']), 'icon' => 'fa-medal', 'classes' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'],
                    ['label' => 'Tingkat Kompetisi', 'value' => number_format($analitikRingkasan['tingkat']), 'icon' => 'fa-layer-group', 'classes' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'],
                    ['label' => 'Tahun aktif', 'value' => number_format($analitikRingkasan['tahun']), 'icon' => 'fa-calendar-alt', 'classes' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300'],
                ];
            @endphp

            @foreach($overview as $item)
                <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-3 text-3xl font-black text-slate-900 dark:text-white">{{ $item['value'] }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl transition duration-300 group-hover:scale-110 {{ $item['classes'] }}">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===================== FILTER + DAFTAR ===================== --}}
    <section x-data="{ filtersOpen: false }" class="mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8 lg:py-10">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-400">Arsip sekolah</p>
                <h2 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">Daftar prestasi</h2>
            </div>
            <button type="button" @click="filtersOpen = true" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-white px-3.5 py-2 text-sm font-bold leading-5 text-emerald-700 shadow-sm transition hover:bg-emerald-50 lg:hidden dark:border-slate-700 dark:bg-slate-900 dark:text-emerald-400 dark:hover:bg-slate-800">
                <i class="fas fa-filter"></i>
                Filter
            </button>
        </div>

        <div class="grid gap-5 lg:grid-cols-[240px_minmax(0,1fr)]">
            <div x-show="filtersOpen" x-cloak @click.self="filtersOpen = false" class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm lg:static lg:z-auto lg:!block lg:bg-transparent lg:backdrop-blur-none">
                <aside class="h-fit w-[min(88vw,320px)] bg-white p-4 shadow-xl dark:bg-slate-900 lg:sticky lg:top-24 lg:w-auto lg:rounded-2xl lg:border lg:border-slate-200 lg:shadow-sm dark:lg:border-slate-700" @click.stop>
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Filter</h3>
                        <button type="button" @click="filtersOpen = false" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 lg:hidden dark:hover:bg-slate-800 dark:hover:text-slate-300" aria-label="Tutup filter">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="prestasi-filter" action="{{ route('public.prestasi.index') }}" method="GET" class="space-y-4">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <fieldset class="space-y-2">
                            <legend class="text-xs font-bold text-slate-900 dark:text-white">Kategori</legend>
                            <div class="space-y-1">
                                @foreach($kategoriOptions->prepend('')->unique() as $value)
                                    @php($label = $value ?: 'Semua')
                                    <label class="flex cursor-pointer items-center gap-2 rounded-lg p-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60">
                                        <input type="radio" name="kategori" value="{{ $value }}" @checked(request('kategori', '') === $value) class="h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-600">
                                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $label === 'Non Akademik' ? 'Non-Akademik' : $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="border-t border-slate-200 pt-4 dark:border-slate-700">
                            <label class="text-xs font-bold text-slate-900 dark:text-white">Tingkat kompetisi</label>
                            <select name="tingkat" class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="">Semua tingkat</option>
                                @foreach($tingkatOptions as $option)
                                    <option value="{{ $option }}" @selected(request('tingkat') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="border-t border-slate-200 pt-4 dark:border-slate-700">
                            <label class="text-xs font-bold text-slate-900 dark:text-white">Tahun</label>
                            <select name="tahun" class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="">Semua tahun</option>
                                @foreach($tahunOptions as $option)
                                    <option value="{{ $option }}" @selected((string) request('tahun') === (string) $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="border-t border-slate-200 pt-4 dark:border-slate-700">
                            <label class="text-xs font-bold text-slate-900 dark:text-white">Jurusan</label>
                            <select name="jurusan" class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="">Semua jurusan</option>
                                @foreach($jurusanOptions as $option)
                                    <option value="{{ $option }}" @selected(request('jurusan') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="border-t border-slate-200 pt-4 dark:border-slate-700">
                            <label class="text-xs font-bold text-slate-900 dark:text-white">Ekstrakurikuler</label>
                            <select name="ekstrakurikuler" class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="">Semua ekstrakurikuler</option>
                                @foreach($ekstrakurikulerOptions as $option)
                                    <option value="{{ $option }}" @selected(request('ekstrakurikuler') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <a href="{{ route('public.prestasi.index') }}" data-ajax-filter-reset class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-center text-xs font-bold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                            Reset filter
                        </a>
                    </form>
                </aside>
            </div>

            <div id="prestasi-results">
                <div class="mb-5 flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Ditemukan <span class="font-black text-emerald-700 dark:text-emerald-400">{{ number_format($prestasis->total()) }}</span> prestasi
                    </p>
                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Live</span>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @forelse($prestasis as $item)
                        <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-900/5 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-700 animate-fade-in">
                            <a href="{{ route('public.prestasi.show', $item->public_token) }}" class="relative block aspect-[4/3] shrink-0 overflow-hidden bg-gradient-to-br from-slate-100 to-emerald-50 dark:from-slate-800 dark:to-slate-900">
                                <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('images/logo-smk.png') }}" alt="{{ $item->nama_lomba }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

                                {{-- Ribbon hasil di pojok foto --}}
                                <div class="absolute left-3 top-3 flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-black uppercase tracking-wide text-amber-700 shadow-lg backdrop-blur dark:bg-slate-900/90 dark:text-amber-400">
                                    <i class="fas fa-medal"></i>
                                    {{ $item->hasil }}
                                </div>
                            </a>

                            <div class="flex min-w-0 flex-1 flex-col p-3.5">
                                <div class="mb-2 flex flex-wrap gap-1.5">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-1 text-[9px] font-bold uppercase tracking-[0.1em] text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        <i class="fas fa-layer-group"></i>
                                        {{ $item->tingkat ?? 'Umum' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1 text-[9px] font-bold uppercase tracking-[0.1em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        <i class="fas fa-tag"></i>
                                        {{ $item->kategori ?? 'Umum' }}
                                    </span>
                                </div>

                                <h3 class="min-h-[2.75rem] break-words text-sm font-black leading-5 text-slate-900 transition group-hover:text-emerald-700 sm:text-base dark:text-white dark:group-hover:text-emerald-400">
                                    <a href="{{ route('public.prestasi.show', $item->public_token) }}" class="block [overflow-wrap:anywhere]">{{ $item->nama_lomba }}</a>
                                </h3>

                                @if($item->nama_tim)
                                    @php($ekstrakurikulerUrl = config('app.ekstrakurikuler.' . $item->nama_tim))
                                    <p class="mt-2 truncate text-sm font-semibold text-blue-700 dark:text-blue-400">
                                        <i class="fas fa-users mr-1"></i>
                                        @if($ekstrakurikulerUrl)
                                            <a href="{{ $ekstrakurikulerUrl }}" target="_blank" rel="noopener noreferrer" class="hover:underline">{{ $item->nama_tim }}</a>
                                        @else
                                            {{ $item->nama_tim }}
                                        @endif
                                    </p>
                                @endif

                                @if($item->tanggal_mulai)
                                    <p class="mt-2 flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                                        <i class="fas fa-calendar text-emerald-600 dark:text-emerald-400"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                                    </p>
                                @endif

                                <div class="mt-auto flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3 text-[11px] text-slate-500 dark:border-slate-800 dark:text-slate-400">
                                    <span>{{ $item->jenis_peserta ?? 'Individu' }}</span>
                                    <a href="{{ route('public.prestasi.show', $item->public_token) }}" class="inline-flex items-center gap-2 font-bold text-emerald-700 transition group-hover:gap-3 dark:text-emerald-400">
                                        Detail
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-[26px] border-2 border-dashed border-slate-300 bg-slate-50 p-12 text-center dark:border-slate-700 dark:bg-slate-900/30">
                            <i class="fas fa-box-open text-4xl text-slate-400 dark:text-slate-600"></i>
                            <p class="mt-4 text-lg font-bold text-slate-700 dark:text-slate-300">Belum ada prestasi yang sesuai</p>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Coba ubah filter untuk melihat data prestasi yang lain.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-10">{{ $prestasis->links() }}</div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('prestasi-filter');
        let filterRequest = null;

        async function applyFilter(url) {
            const results = document.getElementById('prestasi-results');
            history.replaceState({}, '', url);
            results?.classList.add('opacity-50', 'pointer-events-none', 'transition-opacity');
            filterRequest?.abort();
            filterRequest = new AbortController();

            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: filterRequest.signal });
                if (!response.ok) throw new Error('Gagal memuat hasil filter');
                const html = await response.text();
                const documentView = new DOMParser().parseFromString(html, 'text/html');
                const updatedResults = documentView.getElementById('prestasi-results');
                const updatedAnalytics = documentView.getElementById('prestasi-analytics');
                const updatedOverview = documentView.getElementById('prestasi-overview');
                if (updatedResults && results) {
                    results.replaceWith(updatedResults);
                }
                const analytics = document.getElementById('prestasi-analytics');
                const overview = document.getElementById('prestasi-overview');
                if (updatedAnalytics && analytics) analytics.replaceWith(updatedAnalytics);
                if (updatedOverview && overview) overview.replaceWith(updatedOverview);
                const refreshedAnalytics = document.getElementById('prestasi-analytics');
                if (refreshedAnalytics && window.Alpine) window.Alpine.initTree(refreshedAnalytics);
                initPrestasiCharts();
                bindResultLinks();
                document.getElementById('prestasi-results')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (error) {
                if (error.name !== 'AbortError') {
                    window.location.assign(url);
                }
            } finally {
                const refreshedResults = document.getElementById('prestasi-results');
                refreshedResults?.classList.remove('opacity-50', 'pointer-events-none');
            }
        }

        filterForm?.addEventListener('change', function () {
            const url = new URL(this.action, window.location.origin);
            new FormData(this).forEach((value, key) => {
                if (value) url.searchParams.set(key, value);
            });
            applyFilter(url);
        });

        document.getElementById('prestasi-search')?.addEventListener('submit', function (event) {
            event.preventDefault();
            const url = new URL(this.action, window.location.origin);
            new FormData(this).forEach((value, key) => {
                if (value) url.searchParams.set(key, value);
                else url.searchParams.delete(key);
            });
            applyFilter(url);
        });

        document.querySelector('[data-ajax-filter-reset]')?.addEventListener('click', function (event) {
            event.preventDefault();
            applyFilter(new URL(this.href, window.location.origin));
        });

        // Tautan pagination di dalam #prestasi-results juga dimuat lewat AJAX
        // supaya berpindah halaman hasil filter tidak me-refresh seluruh halaman.
        function bindResultLinks() {
            document.querySelectorAll('#prestasi-results nav[role="navigation"] a[href]').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    applyFilter(new URL(this.href, window.location.origin));
                    window.scrollTo({ top: document.getElementById('prestasi-overview')?.offsetTop ?? 0, behavior: 'smooth' });
                });
            });
        }
        bindResultLinks();

        function createChart(id, config) {
            const canvas = document.getElementById(id);
            if (canvas) new Chart(canvas, config);
        }

        function chartOptions(horizontal = false) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (context) => `${context.parsed.y ?? context.parsed} prestasi` } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { weight: '600', size: 10 } } },
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(148, 163, 184, 0.15)' } }
                }
            };
        }

        function initPrestasiCharts() {
            const chartData = JSON.parse(document.getElementById('prestasi-chart-data')?.textContent || '{}');
            createChart('tahunChart', {
                    type: 'bar',
                    data: {
                        labels: chartData.tahunLabels || [],
                        datasets: [{ label: 'Prestasi', data: chartData.tahunData || [], backgroundColor: '#10b981', borderRadius: 8, borderSkipped: false }]
                    },
                    options: chartOptions()
                });

            createChart('jurusanChart', {
                    type: 'bar',
                    data: {
                        labels: chartData.jurusanLabels || [],
                        datasets: [{ label: 'Prestasi', data: chartData.jurusanData || [], backgroundColor: ['#10b981', '#14b8a6', '#0ea5e9', '#f59e0b', '#f97316', '#64748b'], borderRadius: 8, borderSkipped: false }]
                    },
                    options: chartOptions()
                });

            createChart('tahunTrendChart', {
                    type: 'bar',
                    data: {
                        labels: chartData.tahunLabels || [],
                        datasets: [{
                            label: 'Prestasi',
                            data: chartData.tahunData || [],
                            backgroundColor: '#14b8a6',
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: chartOptions()
                });

            createChart('tingkatChart', {
                    type: 'bar',
                    data: {
                        labels: chartData.tingkatLabels || [],
                        datasets: [{ label: 'Prestasi', data: chartData.tingkatData || [], backgroundColor: '#0ea5e9', borderRadius: 8, borderSkipped: false }]
                    },
                    options: chartOptions()
                });

        }
        initPrestasiCharts();
    });
</script>
@endpush
@endsection
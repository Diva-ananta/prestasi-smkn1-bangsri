@extends('layouts.public')

@section('title', 'Siswa Berprestasi - SMK N 1 Bangsri')

@section('content')
@php
    $siswaChartData = [
        'jurusanLabels' => $analitikSiswaJurusan->pluck('jurusan')->all(),
        'jurusanData' => $analitikSiswaJurusan->pluck('total')->all(),
        'angkatanLabels' => $analitikSiswaAngkatan->pluck('angkatan')->all(),
        'angkatanData' => $analitikSiswaAngkatan->pluck('total')->all(),
        'statusLabels' => $analitikSiswaStatus->pluck('status')->all(),
        'statusData' => $analitikSiswaStatus->pluck('total')->all(),
    ];
@endphp
<div class="min-h-screen bg-slate-50 dark:bg-slate-950">
    <section class="relative overflow-hidden border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-emerald-100 blur-3xl dark:bg-emerald-900/20"></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-teal-100 blur-3xl dark:bg-teal-900/10"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
            <div class="grid items-stretch gap-6 lg:grid-cols-2">
                <div class="animate-fade-in flex min-w-0 flex-col">
                <div class="mb-5 inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
                    <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span></span>
                    Direktori sekolah
                </div>
                <h1 class="text-3xl font-black leading-[1.1] tracking-tight text-slate-900 dark:text-white sm:text-5xl">Siswa berprestasi <span class="text-emerald-600 dark:text-emerald-400">SMK N 1 Bangsri</span></h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-300">Temukan siswa dengan riwayat prestasi publik berdasarkan nama, NIS, kelas, jurusan, dan angkatan.</p>
                <form action="{{ route('public.siswa.search') }}" method="GET" class="mt-5 flex flex-col gap-2 sm:flex-row">
                    <label for="hero-student-search" class="sr-only">Cari siswa</label>
                    <div class="relative flex-1"><i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input id="hero-student-search" name="q" value="{{ $keyword }}" type="search" placeholder="Cari nama atau NIS" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pl-11 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white"></div>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700 sm:w-auto"><i class="fas fa-search"></i>Cari siswa</button>
                </form>
                <a href="{{ route('public.prestasi.index') }}" class="mt-3 inline-flex max-w-full items-center gap-2 self-start rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300 dark:hover:bg-emerald-950/50"><i class="fas fa-trophy"></i><span class="truncate">Lihat semua prestasi</span><i class="fas fa-arrow-right shrink-0 text-[10px]"></i></a>
                </div>

                <div id="siswa-analytics" x-data="{ activeChart: 'jurusan', selectChart(name) { this.activeChart = name; requestAnimationFrame(() => window.dispatchEvent(new Event('resize'))); } }" class="min-w-0 rounded-[20px] border border-slate-200 bg-white p-4 shadow-lg dark:border-slate-800 dark:bg-slate-900 sm:rounded-[24px] sm:p-5">
                    <script id="siswa-chart-data" type="application/json">{!! json_encode($siswaChartData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
                    <div class="flex min-h-10 min-w-0 items-center justify-between gap-3"><div class="min-w-0"><p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Analitik siswa</p><h2 class="mt-1 break-words text-base font-bold text-slate-900 dark:text-white sm:text-lg" x-text="{ jurusan: 'Siswa berdasarkan jurusan', angkatan: 'Siswa berdasarkan angkatan', status: 'Siswa berdasarkan status' }[activeChart]"></h2></div><span class="hidden shrink-0 text-xs text-slate-400 sm:inline">Grafik batang</span></div>
                    <div class="mt-4 h-48 sm:h-56"><canvas id="siswaJurusanChart" x-show="activeChart === 'jurusan'"></canvas><canvas id="siswaAngkatanChart" x-show="activeChart === 'angkatan'"></canvas><canvas id="siswaStatusChart" x-show="activeChart === 'status'"></canvas></div>
                    <div class="mt-4 grid grid-cols-3 gap-1.5 border-t border-slate-100 pt-4 dark:border-slate-800 sm:gap-2"><button type="button" @click="selectChart('jurusan')" :class="activeChart === 'jurusan' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-lg px-1 py-2 text-[11px] font-bold transition sm:px-2 sm:text-xs">Jurusan</button><button type="button" @click="selectChart('angkatan')" :class="activeChart === 'angkatan' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-lg px-1 py-2 text-[11px] font-bold transition sm:px-2 sm:text-xs">Angkatan</button><button type="button" @click="selectChart('status')" :class="activeChart === 'status' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-lg px-1 py-2 text-[11px] font-bold transition sm:px-2 sm:text-xs">Status</button></div>
            </div>
        </div>
    </section>

    <main x-data="{ filtersOpen: false }" class="mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8 lg:py-10">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div><p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-400">Arsip siswa</p><h2 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">Daftar siswa</h2></div>
            <button type="button" @click="filtersOpen = true" class="inline-flex min-h-10 items-center justify-center gap-2 self-start rounded-xl border border-emerald-200 bg-white px-3.5 py-2 text-sm font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-50 lg:hidden dark:border-slate-700 dark:bg-slate-900 dark:text-emerald-400"><i class="fas fa-filter"></i>Filter</button>
        </div>

        <div class="grid gap-5 lg:grid-cols-[240px_minmax(0,1fr)]">
            <div x-show="filtersOpen" x-cloak @click.self="filtersOpen = false" class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm lg:static lg:z-auto lg:!block lg:bg-transparent lg:backdrop-blur-none">
                <aside class="h-fit w-[min(88vw,320px)] bg-white p-4 shadow-xl dark:bg-slate-900 lg:sticky lg:top-24 lg:w-auto lg:rounded-2xl lg:border lg:border-slate-200 lg:shadow-sm dark:lg:border-slate-700" @click.stop>
                    <div class="mb-4 flex items-center justify-between"><h3 class="text-base font-bold text-slate-900 dark:text-white">Filter</h3><button type="button" @click="filtersOpen = false" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800" aria-label="Tutup filter"><i class="fas fa-times"></i></button></div>
                    <form id="siswa-filter" action="{{ route('public.siswa.search') }}" method="GET" class="space-y-3">
                        <div class="border-t border-slate-200 pt-3 dark:border-slate-700"><label class="text-xs font-bold text-slate-900 dark:text-white">Jurusan</label><select name="jurusan" class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white"><option value="">Semua jurusan</option>@foreach($jurusanOptions as $option)<option value="{{ $option }}" @selected(request('jurusan') === $option)>{{ $option }}</option>@endforeach</select></div>
                        <div class="border-t border-slate-200 pt-3 dark:border-slate-700"><label class="text-xs font-bold text-slate-900 dark:text-white">Kelas</label><select name="kelas" class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white"><option value="">Semua kelas</option>@foreach($kelasOptions as $option)<option value="{{ $option }}" @selected(request('kelas') === $option)>{{ $option }}</option>@endforeach</select></div>
                        <div class="border-t border-slate-200 pt-3 dark:border-slate-700"><label class="text-xs font-bold text-slate-900 dark:text-white">Angkatan</label><select name="angkatan" class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white"><option value="">Semua angkatan</option>@foreach($angkatanOptions as $option)<option value="{{ $option }}" @selected((string) request('angkatan') === (string) $option)>{{ $option }}</option>@endforeach</select></div>
                        <div class="border-t border-slate-200 pt-3 dark:border-slate-700"><label class="text-xs font-bold text-slate-900 dark:text-white">Status</label><select name="status" class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white"><option value="">Semua status</option><option value="Aktif" @selected(request('status') === 'Aktif')>Aktif</option><option value="Alumni" @selected(request('status') === 'Alumni')>Alumni</option></select></div>
                        <a href="{{ route('public.siswa.search') }}" data-ajax-filter-reset class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-center text-xs font-bold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Reset filter</a>
                    </form>
                </aside>
            </div>

            <div id="siswa-results">
                <div class="mb-5 flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-900"><p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Ditemukan <span class="font-black text-emerald-700 dark:text-emerald-400">{{ number_format($siswas->total()) }}</span> siswa</p><span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Publish</span></div>
                @if($keyword !== '' || request()->hasAny(['jurusan', 'kelas', 'angkatan', 'status']))
                    <div role="status" aria-live="polite" class="mb-5 flex items-start gap-3 rounded-xl border {{ $siswas->total() ? 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200' : 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200' }} px-4 py-3 text-sm">
                        <i class="fas {{ $siswas->total() ? 'fa-circle-check' : 'fa-circle-info' }} mt-0.5 shrink-0"></i>
                        <p>{{ $siswas->total() ? 'Pencarian berhasil. Menampilkan ' . number_format($siswas->total()) . ' siswa yang sesuai.' : 'Siswa tidak ditemukan. Coba gunakan kata kunci atau filter yang berbeda.' }}</p>
                    </div>
                @endif
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($siswas as $siswa)
                @php $initials = collect(explode(' ', trim($siswa->nama)))->filter()->map(fn ($word) => strtoupper(substr($word, 0, 1)))->take(2)->join(''); @endphp
                <article class="group flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-700 animate-fade-in">
                    <div class="flex items-start gap-3">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-emerald-100 text-lg font-black text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                            @if($siswa->foto)<img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama }}" class="h-full w-full object-cover">@else{{ $initials }}@endif
                        </div>
                        <div class="min-w-0"><h2 class="break-words text-base font-bold text-slate-900 dark:text-white">{{ $siswa->nama }}</h2><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">NIS: {{ $siswa->nis ?: '-' }}</p></div>
                    </div>
                    <dl class="mt-4 grid grid-cols-2 gap-2.5 border-t border-slate-100 pt-3 text-xs dark:border-slate-800">
                        <div><dt class="text-slate-400">{{ $siswa->status === 'Alumni' ? 'Kelas terakhir' : 'Kelas' }}</dt><dd class="mt-1 font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->kelas ?: '-' }}</dd></div>
                        <div><dt class="text-slate-400">Jurusan</dt><dd class="mt-1 break-words font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->jurusan ?: '-' }}</dd></div>
                        <div><dt class="text-slate-400">Angkatan</dt><dd class="mt-1 font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->angkatan ?: '-' }}</dd></div>
                        <div><dt class="text-slate-400">Prestasi</dt><dd class="mt-1 font-semibold text-emerald-700 dark:text-emerald-400">{{ $siswa->prestasi_publish_count }} capaian</dd></div>
                    </dl>
                    <div class="mt-auto flex items-center justify-between gap-3 border-t border-slate-100 pt-3 dark:border-slate-800"><span class="text-xs font-semibold {{ $siswa->status === 'Aktif' ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }}">{{ $siswa->status }}</span><a href="{{ route('public.siswa.profile', ['nama' => \Illuminate\Support\Str::slug($siswa->nama)]) }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-700 transition group-hover:gap-3 dark:text-emerald-400">Profil <i class="fas fa-arrow-right text-[10px]"></i></a></div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-300 p-12 text-center dark:border-slate-700"><i class="fas fa-user-slash text-4xl text-slate-400"></i><p class="mt-4 text-lg font-bold text-slate-700 dark:text-slate-300">Belum ada siswa yang sesuai</p><p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Coba ubah kata kunci atau filter yang dipilih.</p></div>
            @endforelse
                </div>
                <div class="mt-10">{{ $siswas->links() }}</div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('siswa-filter');
        let filterRequest = null;

        async function applyFilter(url) {
            const results = document.getElementById('siswa-results');
            history.replaceState({}, '', url);
            results?.classList.add('opacity-50', 'pointer-events-none', 'transition-opacity');
            filterRequest?.abort();
            filterRequest = new AbortController();

            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: filterRequest.signal });
                if (!response.ok) throw new Error('Gagal memuat hasil filter');
                const html = await response.text();
                const updatedResults = new DOMParser().parseFromString(html, 'text/html').getElementById('siswa-results');
                if (updatedResults && results) results.replaceWith(updatedResults);
                bindResultLinks();
            } catch (error) {
                if (error.name !== 'AbortError') window.location.assign(url);
            } finally {
                document.getElementById('siswa-results')?.classList.remove('opacity-50', 'pointer-events-none');
            }
        }

        filterForm?.addEventListener('change', function () {
            const url = new URL(this.action, window.location.origin);
            new FormData(this).forEach((value, key) => { if (value) url.searchParams.set(key, value); else url.searchParams.delete(key); });
            applyFilter(url);
        });
        filterForm?.addEventListener('submit', function (event) {
            event.preventDefault();
            const url = new URL(this.action, window.location.origin);
            new FormData(this).forEach((value, key) => { if (value) url.searchParams.set(key, value); });
            applyFilter(url);
        });
        document.querySelector('[data-ajax-filter-reset]')?.addEventListener('click', function (event) { event.preventDefault(); applyFilter(new URL(this.href, window.location.origin)); });

        function bindResultLinks() {
            document.querySelectorAll('#siswa-results nav[role="navigation"] a[href]').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    applyFilter(new URL(this.href, window.location.origin));
                    window.scrollTo({ top: document.getElementById('siswa-results')?.offsetTop ?? 0, behavior: 'smooth' });
                });
            });
        }
        bindResultLinks();

        const chartData = JSON.parse(document.getElementById('siswa-chart-data')?.textContent || '{}');
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (context) => `${context.parsed.y ?? context.parsed} siswa` } } },
            scales: { x: { grid: { display: false }, ticks: { color: '#64748b', font: { weight: '600', size: 10 } } }, y: { beginAtZero: true, ticks: { precision: 0, color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(148, 163, 184, 0.15)' } } }
        };
        function createSiswaChart(id, labels, data, color) {
            const canvas = document.getElementById(id);
            if (canvas) new Chart(canvas, { type: 'bar', data: { labels: labels || [], datasets: [{ label: 'Siswa', data: data || [], backgroundColor: color, borderRadius: 8, borderSkipped: false }] }, options: chartOptions });
        }
        createSiswaChart('siswaJurusanChart', chartData.jurusanLabels, chartData.jurusanData, ['#10b981', '#14b8a6', '#0ea5e9', '#f59e0b', '#f97316', '#64748b']);
        createSiswaChart('siswaAngkatanChart', chartData.angkatanLabels, chartData.angkatanData, '#14b8a6');
        createSiswaChart('siswaStatusChart', chartData.statusLabels, chartData.statusData, ['#10b981', '#f59e0b']);
    });
</script>
@endpush
@endsection

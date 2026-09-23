@extends('layouts.admin')

@section('title', 'Dashboard')

@push('head-scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="page-shell">
    {{-- ===== HEADER ===== --}}
    <div class="page-header animate-fade-in overflow-hidden">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Dashboard administrasi
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800 dark:text-white md:text-3xl">Selamat datang, {{ Auth::user()->name }}</h1>
                <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-300">Pantau perkembangan prestasi siswa dan aktivitas terbaru sekolah dalam satu tempat.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    <i class="far fa-calendar-alt text-emerald-600 dark:text-emerald-400"></i>
                    {{ now()->translatedFormat('d M Y') }}
                </div>
                <a href="{{ route('admin.prestasi.create') }}" class="admin-btn-primary rounded-xl px-3.5 py-2" aria-label="Tambah prestasi baru">
                    <i class="fas fa-plus mr-2 text-xs"></i>Tambah Prestasi
                </a>
            </div>
        </div>
    </div>

    {{-- ===== QUICK ACTIONS ===== --}}
    <div class="grid grid-cols-2 gap-3 animate-fade-in sm:grid-cols-4">
        @php
            $quickActions = [
                ['label' => 'Tambah Siswa', 'icon' => 'fas fa-user-plus', 'route' => 'admin.siswa.create', 'color' => 'text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300'],
                ['label' => 'Tambah Prestasi', 'icon' => 'fas fa-award', 'route' => 'admin.prestasi.create', 'color' => 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-300'],
                ['label' => 'Kelola Artikel', 'icon' => 'fas fa-newspaper', 'route' => 'admin.artikel.index', 'color' => 'text-violet-600 bg-violet-50 dark:bg-violet-900/30 dark:text-violet-300'],
                ['label' => 'Kelola Galeri', 'icon' => 'fas fa-images', 'route' => 'admin.galeri.index', 'color' => 'text-amber-600 bg-amber-50 dark:bg-amber-900/30 dark:text-amber-300'],
            ];
        @endphp
        @foreach($quickActions as $action)
            <a href="{{ route($action['route']) }}" class="section-card flex items-center gap-3 !p-3.5 transition hover:-translate-y-0.5 hover:shadow-md">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $action['color'] }}">
                    <i class="{{ $action['icon'] }}"></i>
                </span>
                <span class="min-w-0 truncate text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $action['label'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- ===== METRIC CARDS ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 animate-fade-in">
        @php
            $metrics = [
                ['label' => 'Total Siswa', 'value' => number_format($totalSiswa), 'icon' => 'fas fa-users', 'color' => 'from-blue-500 to-blue-600', 'accent' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300', 'note' => number_format($siswaAktif ?? 0) . ' siswa aktif'],
                ['label' => 'Total Prestasi', 'value' => number_format($totalPrestasi), 'icon' => 'fas fa-trophy', 'color' => 'from-emerald-500 to-green-600', 'accent' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300', 'note' => number_format($prestasiPublish ?? 0) . ' sudah dipublish'],
                ['label' => 'Siswa Berprestasi', 'value' => number_format($totalSiswaBerprestasi), 'icon' => 'fas fa-star', 'color' => 'from-violet-500 to-purple-600', 'accent' => 'bg-violet-100 text-violet-600 dark:bg-violet-900/40 dark:text-violet-300', 'note' => $totalSiswa > 0 ? round(($totalSiswaBerprestasi / max($totalSiswa, 1)) * 100) . '% dari total siswa' : 'Belum ada data'],
                ['label' => 'Kunjungan Website', 'value' => number_format($websiteViews ?? 0), 'icon' => 'fas fa-eye', 'color' => 'from-amber-500 to-orange-500', 'accent' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300', 'note' => 'Halaman publik sekolah'],
            ];
        @endphp

        @foreach($metrics as $metric)
            <div class="metric-card group relative flex min-h-[170px] flex-col justify-between overflow-hidden">
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full {{ $metric['accent'] }} opacity-30 blur-2xl"></div>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $metric['label'] }}</p>
                        <p class="mt-3 text-2xl font-bold text-slate-800 dark:text-white md:text-[2rem]">{{ $metric['value'] }}</p>
                    </div>
                    <div class="soft-ring flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br {{ $metric['color'] }} text-base text-white shadow-lg transition-transform duration-200 group-hover:scale-105">
                        <i class="{{ $metric['icon'] }}"></i>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-2 border-t border-slate-100 pt-3 text-[11px] font-medium text-slate-400 dark:border-slate-800 dark:text-slate-500">
                    <i class="fas fa-circle-check text-emerald-500"></i>
                    <span class="truncate">{{ $metric['note'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== CHART + TOP SISWA ===== --}}
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(280px,360px)]">
        <div class="section-card animate-fade-in min-w-0">
            <div class="mb-5 flex flex-col gap-3">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Tren Prestasi</h2>
                        <p id="chartPrestasiLabel" class="text-xs text-slate-500 dark:text-slate-400">
                            @if(($rangeType ?? 'year') === 'all')
                                Semua tahun
                            @else
                                Periode 1 tahun
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="hidden rounded-lg bg-emerald-50 px-2.5 py-1.5 text-[11px] font-bold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 sm:inline"><span id="chartTotal">{{ number_format(array_sum($chartData ?? [])) }}</span> prestasi</span>
                        <button type="button" id="toggleChartFilter" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                            <i class="fas fa-filter text-[10px]"></i>
                            Filter
                        </button>
                    </div>
                </div>

                <div id="chartFilterPanel" class="hidden rounded-2xl border border-slate-200 bg-slate-50/80 p-2.5 dark:border-slate-700 dark:bg-slate-900/60">
                    <form id="chartFilterForm" data-chart-url="{{ route('admin.dashboard.chart') }}" class="flex flex-col gap-2">
                        <div class="flex flex-wrap items-end gap-2">
                            <label class="flex min-w-[150px] flex-col gap-1 text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500 dark:text-slate-400">
                                <span>Periode</span>
                                <select name="range_type" class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-[10px] font-medium text-slate-700 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200" aria-label="Pilih periode grafik">
                                    <option value="year" {{ ($rangeType ?? 'year') === 'year' ? 'selected' : '' }}>1 Tahun</option>
                                    <option value="all" {{ ($rangeType ?? 'year') === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                                </select>
                            </label>

                            <label class="flex min-w-[130px] flex-col gap-1 text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500 dark:text-slate-400">
                                <span>Mulai</span>
                                <input type="date" name="start_date" value="{{ request('start_date') ?: ($startDate ?? now()->subYear())->format('Y-m-d') }}" class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-[10px] text-slate-700 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200" aria-label="Tanggal mulai">
                            </label>

                            <label class="flex min-w-[130px] flex-col gap-1 text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500 dark:text-slate-400">
                                <span>Sampai</span>
                                <input type="date" name="end_date" value="{{ request('end_date') ?: ($endDate ?? now())->format('Y-m-d') }}" class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-[10px] text-slate-700 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200" aria-label="Tanggal akhir">
                            </label>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" id="resetChartFilter" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Reset</button>
                            <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-[10px] font-semibold text-white transition hover:bg-emerald-500">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="pb-1">
                <div class="h-[300px] rounded-2xl border border-slate-100 bg-gradient-to-b from-emerald-50/50 to-transparent p-3 sm:h-[330px] dark:border-slate-800 dark:from-emerald-950/10">
                    <canvas id="chartPrestasi"></canvas>
                </div>
            </div>
        </div>

        <div class="section-card animate-fade-in flex min-h-[400px] w-full min-w-0 flex-col xl:justify-self-end">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Top Siswa</h2>
                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">Top 5</span>
            </div>

            @if($topSiswa->count())
                <div class="min-h-0 flex-1 space-y-2 overflow-y-auto pr-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    @foreach($topSiswa as $index => $item)
                        <a href="{{ $item->siswa && $item->siswa->public_token ? route('public.siswa.show', ['token' => $item->siswa->public_token]) : '#' }}" class="block w-full min-w-0 overflow-hidden rounded-2xl border border-transparent bg-slate-50 p-3 transition-colors hover:border-slate-200 hover:bg-white dark:bg-slate-800/60 dark:hover:border-slate-700 dark:hover:bg-slate-800">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex min-w-0 flex-1 items-center gap-3">
                                    <div class="relative flex h-11 w-11 shrink-0 overflow-hidden rounded-full border-2 border-white shadow-sm dark:border-slate-900">
                                        @if($item->siswa && $item->siswa->foto)
                                            <img src="{{ asset('storage/' . $item->siswa->foto) }}" alt="{{ $item->siswa->nama ?? 'Foto siswa' }}" class="h-full w-full object-cover" loading="lazy">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center {{ $index === 0 ? 'bg-gradient-to-br from-amber-400 to-orange-500' : 'bg-gradient-to-br from-blue-500 to-indigo-600' }} text-xs font-bold text-white">
                                                {{ strtoupper(substr(($item->siswa->nama ?? 'S')[0] ?? 'S', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full {{ $index === 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-200' }} text-[10px] font-bold">
                                                {{ $index + 1 }}
                                            </span>
                                            <p class="truncate font-semibold text-slate-800 dark:text-white">{{ $item->siswa->nama ?? '-' }}</p>
                                        </div>
                                        <p class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400">{{ $item->siswa->kelas ?? '-' }} • {{ $item->siswa->jurusan ?? '-' }}</p>
                                    </div>
                                </div>
                                <span class="ml-1 shrink-0 rounded-lg bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">{{ $item->total }}x</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex flex-1 flex-col items-center justify-center gap-2 text-center">
                    <i class="fas fa-star text-3xl text-slate-300 dark:text-slate-600"></i>
                    <p class="text-sm text-slate-400">Belum ada data siswa berprestasi.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ===== INSIGHT: DISTRIBUSI TINGKAT & STATUS SISWA ===== --}}
    <div class="grid gap-6 lg:grid-cols-2 animate-fade-in">
        <div class="section-card">
            <div class="section-card">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Distribusi Tingkat Kompetisi</h2>
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">Top 5</span>
            </div>

            @if(($prestasiByTingkat ?? collect())->count())
                <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-center">
                    <div class="relative h-[200px] w-[200px] shrink-0">
                        <canvas id="chartTingkat"></canvas>
                        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($prestasiByTingkat->sum('total')) }}</span>
                            <span class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Prestasi</span>
                        </div>
                    </div>

                    <div class="w-full min-w-0 flex-1 space-y-2.5">
                        @php
                            $tingkatColors = ['#059669', '#0ea5e9', '#8b5cf6', '#f59e0b', '#f43f5e'];
                        @endphp
                        @foreach($prestasiByTingkat as $i => $item)
                            <div class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-800/60">
                                <div class="flex min-w-0 items-center gap-2">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $tingkatColors[$i % count($tingkatColors)] }}"></span>
                                    <span class="truncate text-sm font-medium text-slate-700 dark:text-slate-200">{{ $item->tingkat }}</span>
                                </div>
                                <span class="shrink-0 text-sm font-bold text-slate-800 dark:text-white">{{ $item->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center gap-2 py-8 text-center">
                    <i class="fas fa-layer-group text-3xl text-slate-300 dark:text-slate-600"></i>
                    <p class="text-sm text-slate-400">Belum ada data tingkat kompetisi.</p>
                </div>
            @endif
        </div>

        <div class="section-card">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Status Data</h2>
                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">Ringkasan</span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/60">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Siswa Aktif</p>
                    <p class="mt-1 text-xl font-bold text-slate-800 dark:text-white">{{ number_format($siswaAktif ?? 0) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/60">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Alumni</p>
                    <p class="mt-1 text-xl font-bold text-slate-800 dark:text-white">{{ number_format($siswaAlumni ?? 0) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/60">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Prestasi Publish</p>
                    <p class="mt-1 text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($prestasiPublish ?? 0) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/60">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Prestasi Draft</p>
                    <p class="mt-1 text-xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($prestasiDraft ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PRESTASI TERBARU ===== --}}
    <div class="section-card animate-fade-in">
        <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Prestasi Terbaru</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Aktivitas terbaru dari data sekolah</p>
            </div>
            <a href="{{ route('admin.prestasi.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50">
                Lihat semua <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="admin-table-wrap overflow-x-auto">
            <table class="admin-table min-w-[760px] min-w-full text-left text-sm">
                <thead>
                    <tr>
                        <th>Nama Lomba</th>
                        <th>Siswa</th>
                        <th>Tingkat</th>
                        <th>Hasil</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestasiTerbaru as $prestasi)
                        <tr>
                            <td class="max-w-xs font-semibold text-slate-700 dark:text-slate-200"><span class="line-clamp-2">{{ $prestasi->nama_lomba ?? '-' }}</span></td>
                            <td class="text-slate-600 dark:text-slate-300">
                                @if($prestasi->detailPrestasi->count())
                                    {{ $prestasi->detailPrestasi->first()->siswa->nama ?? '-' }}
                                    @if($prestasi->detailPrestasi->count() > 1)
                                        <span class="ml-1 text-xs text-slate-400">+{{ $prestasi->detailPrestasi->count() - 1 }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-slate-600 dark:text-slate-300">{{ $prestasi->tingkat ?? '-' }}</td>
                            <td class="text-slate-600 dark:text-slate-300">{{ $prestasi->hasil }}</td>
                            <td>
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $prestasi->status == 'Publish' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                    {{ $prestasi->status }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap text-slate-500 dark:text-slate-400">{{ $prestasi->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-sm text-slate-400">
                                <i class="fas fa-trophy mb-3 block text-2xl text-slate-300 dark:text-slate-600"></i>
                                Belum ada prestasi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartCanvas = document.getElementById('chartPrestasi');
        const chartLabel = document.getElementById('chartPrestasiLabel');
        const chartTotal = document.getElementById('chartTotal');
        const chartLabels = @json($chartLabels ?? []);
        const chartData = @json($chartData ?? []);

        // ===== 1. TREN PRESTASI: Area / line chart dengan gradient =====
        let chartPrestasi = null;

        if (chartCanvas && typeof Chart !== 'undefined') {
            const ctx = chartCanvas.getContext('2d');
            const rangeType = @json($rangeType ?? 'year');
            const filterToggle = document.getElementById('toggleChartFilter');
            const filterPanel = document.getElementById('chartFilterPanel');

            const buildGradient = (context) => {
                const chartArea = context.chart.chartArea;
                if (!chartArea) return 'rgba(16,185,129,0.25)';
                const gradient = context.chart.ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                gradient.addColorStop(0, 'rgba(16,185,129,0.35)');
                gradient.addColorStop(1, 'rgba(16,185,129,0.02)');
                return gradient;
            };

            chartPrestasi = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: rangeType === 'all' ? 'Prestasi semua tahun' : 'Prestasi dalam periode',
                        data: chartData,
                        fill: true,
                        backgroundColor: buildGradient,
                        borderColor: '#059669',
                        borderWidth: 2.5,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#047857',
                        pointHoverBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 600, easing: 'easeOutQuart' },
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: (items) => items[0]?.label ?? '',
                                label: (context) => `${context.parsed.y ?? context.parsed} prestasi tercatat`
                            },
                            backgroundColor: 'rgba(15,23,42,0.96)',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            borderColor: 'rgba(148,163,184,0.3)',
                            borderWidth: 1,
                            cornerRadius: 12,
                            displayColors: false,
                            padding: 12
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#64748b',
                                font: { weight: '600', size: 10 },
                                autoSkip: true,
                                maxTicksLimit: 8,
                                maxRotation: 0,
                                minRotation: 0
                            }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...chartData, 1) + 1,
                            ticks: { precision: 0, color: '#64748b', font: { size: 10 } },
                            grid: { color: 'rgba(148,163,184,0.14)', borderDash: [4, 4], drawBorder: false }
                        }
                    }
                }
            });

            const chartText = rangeType === 'all' ? 'Semua tahun' : 'Periode 1 tahun';
            if (chartLabel) chartLabel.textContent = chartText;
            if (chartTotal) chartTotal.textContent = chartData.reduce((t, v) => t + Number(v || 0), 0).toLocaleString('id-ID');

            if (filterToggle && filterPanel) {
                filterToggle.addEventListener('click', () => filterPanel.classList.toggle('hidden'));
            }

            const filterForm = document.getElementById('chartFilterForm');
            const resetButton = document.getElementById('resetChartFilter');

            if (filterForm) {
                filterForm.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    const formData = new FormData(filterForm);
                    const params = new URLSearchParams(formData);
                    const chartUrl = `${filterForm.dataset.chartUrl}?${params.toString()}`;

                    try {
                        const response = await fetch(chartUrl, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        });
                        if (!response.ok) throw new Error('Gagal memuat data grafik');
                        const payload = await response.json();

                        chartPrestasi.data.labels = payload.chartLabels ?? [];
                        chartPrestasi.data.datasets[0].data = payload.chartData ?? [];
                        chartPrestasi.data.datasets[0].label = payload.rangeType === 'all' ? 'Prestasi semua tahun' : 'Prestasi dalam periode';
                        chartPrestasi.options.scales.y.suggestedMax = Math.max(...(payload.chartData ?? [0]), 1) + 1;
                        chartPrestasi.update();

                        if (chartLabel) chartLabel.textContent = payload.labelText ?? 'Periode 1 tahun';
                        if (chartTotal) chartTotal.textContent = (payload.chartData ?? []).reduce((t, v) => t + Number(v || 0), 0).toLocaleString('id-ID');

                        const queryString = params.toString();
                        const url = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
                        window.history.replaceState({}, '', url);
                    } catch (error) {
                        console.error(error);
                    }
                });
            }

            if (resetButton && filterForm) {
                resetButton.addEventListener('click', function () {
                    filterForm.reset();
                    window.location.href = '{{ route('admin.dashboard') }}';
                });
            }
        }

        // ===== 2. DOUGHNUT CHART: Distribusi Tingkat Kompetisi =====
        const tingkatCanvas = document.getElementById('chartTingkat');
        if (tingkatCanvas && typeof Chart !== 'undefined') {
            const tingkatLabels = @json(($prestasiByTingkat ?? collect())->pluck('tingkat'));
            const tingkatData = @json(($prestasiByTingkat ?? collect())->pluck('total'));
            const tingkatColors = ['#059669', '#0ea5e9', '#8b5cf6', '#f59e0b', '#f43f5e'];

            new Chart(tingkatCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: tingkatLabels,
                    datasets: [{
                        data: tingkatData,
                        backgroundColor: tingkatColors,
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    animation: { duration: 600, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.label}: ${context.parsed} prestasi`
                            },
                            backgroundColor: 'rgba(15,23,42,0.96)',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            borderColor: 'rgba(148,163,184,0.3)',
                            borderWidth: 1,
                            cornerRadius: 12,
                            displayColors: true,
                            padding: 10
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
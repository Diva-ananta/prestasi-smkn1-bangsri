@extends('layouts.admin')

@section('title', 'Dashboard')

@push('head-scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="page-shell">
    <div class="page-header animate-fade-in overflow-hidden">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
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

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4 animate-fade-in">
        @php
            $metrics = [
                ['label' => 'Total Siswa', 'value' => number_format($totalSiswa), 'icon' => 'fas fa-users', 'color' => 'from-blue-500 to-blue-600', 'accent' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300'],
                ['label' => 'Total Prestasi', 'value' => number_format($totalPrestasi), 'icon' => 'fas fa-trophy', 'color' => 'from-emerald-500 to-green-600', 'accent' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300'],
                ['label' => 'Prestasi Tim', 'value' => number_format($totalPrestasiTim), 'icon' => 'fas fa-people-group', 'color' => 'from-violet-500 to-purple-600', 'accent' => 'bg-violet-100 text-violet-600 dark:bg-violet-900/40 dark:text-violet-300'],
                ['label' => 'Siswa Berprestasi', 'value' => number_format($totalSiswaBerprestasi), 'icon' => 'fas fa-star', 'color' => 'from-amber-500 to-orange-500', 'accent' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300'],
            ];
        @endphp

        @foreach($metrics as $metric)
            <div class="metric-card group relative overflow-hidden">
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full {{ $metric['accent'] }} opacity-30 blur-2xl"></div>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $metric['label'] }}</p>
                        <p class="mt-3 text-3xl font-bold text-slate-800 dark:text-white">{{ $metric['value'] }}</p>
                    </div>
                    <div class="soft-ring flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br {{ $metric['color'] }} text-lg text-white shadow-lg transition-transform duration-200 group-hover:scale-105">
                        <i class="{{ $metric['icon'] }}"></i>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-2 border-t border-slate-100 pt-4 text-xs font-medium text-slate-400 dark:border-slate-800 dark:text-slate-500">
                    <i class="fas fa-circle-check text-emerald-500"></i>
                    <span>Data sistem terkini</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.6fr_0.9fr]">
        <div class="section-card animate-fade-in">
            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Tren Prestasi Bulanan</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ now()->subMonths(5)->translatedFormat('M Y') }} - {{ now()->translatedFormat('M Y') }}</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    Prestasi
                </div>
            </div>
            <div class="h-72">
                <canvas id="chartPrestasi"></canvas>
            </div>
        </div>

        <div class="section-card animate-fade-in">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Top Siswa</h2>
                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">Ranking</span>
            </div>

            @if($topSiswa->count())
                <div class="space-y-2">
                    @foreach($topSiswa as $index => $item)
                        <div class="flex items-center justify-between rounded-2xl border border-transparent bg-slate-50 p-3 transition-colors hover:border-slate-200 hover:bg-white dark:bg-slate-800/60 dark:hover:border-slate-700 dark:hover:bg-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $index === 0 ? 'bg-gradient-to-br from-amber-400 to-orange-500' : 'bg-gradient-to-br from-blue-500 to-indigo-600' }} text-sm font-bold text-white shadow-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-800 dark:text-white">{{ $item->siswa->nama ?? '-' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item->siswa->kelas ?? '-' }} • {{ $item->siswa->jurusan ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="ml-3 shrink-0 rounded-lg bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">{{ $item->total }}x</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400">Belum ada data siswa berprestasi.</p>
            @endif
        </div>
    </div>

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
            <table class="admin-table min-w-[720px] min-w-full text-left text-sm">
                <thead>
                    <tr>
                        <th>Nama Lomba</th>
                        <th>Siswa</th>
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
                                @else
                                    -
                                @endif
                            </td>
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
                            <td colspan="5" class="py-10 text-center text-sm text-slate-400">
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
        if (document.getElementById('chartPrestasi') && typeof Chart !== 'undefined') {
            const ctx = document.getElementById('chartPrestasi').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($bulanLabels),
                    datasets: [{
                        label: 'Prestasi',
                        data: @json($bulanData),
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15,23,42,0.92)',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            borderColor: 'rgba(148,163,184,0.3)',
                            borderWidth: 1,
                            cornerRadius: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#64748b' },
                            grid: { color: 'rgba(148,163,184,0.16)' }
                        },
                        x: {
                            ticks: { color: '#64748b' },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection

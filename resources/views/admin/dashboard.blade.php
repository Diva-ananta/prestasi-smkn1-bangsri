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

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 animate-fade-in">
        @php
            $metrics = [
                ['label' => 'Total Siswa', 'value' => number_format($totalSiswa), 'icon' => 'fas fa-users', 'color' => 'from-blue-500 to-blue-600', 'accent' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300'],
                ['label' => 'Total Prestasi', 'value' => number_format($totalPrestasi), 'icon' => 'fas fa-trophy', 'color' => 'from-emerald-500 to-green-600', 'accent' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300'],
                ['label' => 'Siswa Berprestasi', 'value' => number_format($totalSiswaBerprestasi), 'icon' => 'fas fa-star', 'color' => 'from-violet-500 to-purple-600', 'accent' => 'bg-violet-100 text-violet-600 dark:bg-violet-900/40 dark:text-violet-300'],
                ['label' => 'Kunjungan Website', 'value' => number_format($websiteViews ?? 0), 'icon' => 'fas fa-eye', 'color' => 'from-amber-500 to-orange-500', 'accent' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300'],
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
                    <span>Data sistem terkini</span>
                </div>
            </div>
        @endforeach
    </div>

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
        const chartCanvas = document.getElementById('chartPrestasi');
        const chartLabel = document.getElementById('chartPrestasiLabel');
        const chartTotal = document.getElementById('chartTotal');
        const chartLabels = @json($chartLabels ?? []);
        const chartData = @json($chartData ?? []);

        if (chartCanvas && typeof Chart !== 'undefined') {
            const ctx = chartCanvas.getContext('2d');
            const rangeType = @json($rangeType ?? 'year');
            const filterToggle = document.getElementById('toggleChartFilter');
            const filterPanel = document.getElementById('chartFilterPanel');
            const barGradient = ctx.createLinearGradient(0, 0, 0, chartCanvas.clientHeight || 330);
            barGradient.addColorStop(0, '#14b8a6');
            barGradient.addColorStop(1, '#059669');

            if (filterToggle && filterPanel) {
                filterToggle.addEventListener('click', function () {
                    filterPanel.classList.toggle('hidden');
                });
            }

            const chartPrestasi = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: rangeType === 'all' ? 'Prestasi semua tahun' : 'Prestasi dalam periode',
                        data: chartData,
                        backgroundColor: barGradient,
                        borderColor: '#047857',
                        borderWidth: 1,
                        borderRadius: 10,
                        borderSkipped: false,
                        maxBarThickness: 42,
                        hoverBackgroundColor: '#0f766e',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 500,
                        easing: 'easeOutQuart'
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
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
                            },
                            categoryPercentage: 0.8,
                            barPercentage: 0.7
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...chartData, 1) + 1,
                            ticks: {
                                precision: 0,
                                color: '#64748b',
                                font: { size: 10 }
                            },
                            grid: {
                                color: 'rgba(148,163,184,0.14)',
                                borderDash: [4, 4],
                                drawBorder: false
                            }
                        }
                    }
                }
            });

            const chartText = rangeType === 'all' ? 'Semua tahun' : 'Periode 1 tahun';
            if (chartLabel) {
                chartLabel.textContent = chartText;
            }
            if (chartTotal) {
                chartTotal.textContent = chartData.reduce((total, value) => total + Number(value || 0), 0).toLocaleString('id-ID');
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
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Gagal memuat data grafik');
                        }

                        const payload = await response.json();

                        chartPrestasi.data.labels = payload.chartLabels ?? [];
                        chartPrestasi.data.datasets[0].data = payload.chartData ?? [];
                        chartPrestasi.data.datasets[0].label = payload.rangeType === 'all' ? 'Prestasi semua tahun' : 'Prestasi dalam periode';
                        chartPrestasi.options.scales.y.suggestedMax = Math.max(...(payload.chartData ?? [0]), 1) + 1;
                        chartPrestasi.update();

                        if (chartLabel) {
                            chartLabel.textContent = payload.labelText ?? 'Periode 1 tahun';
                        }
                        if (chartTotal) {
                            chartTotal.textContent = (payload.chartData ?? []).reduce((total, value) => total + Number(value || 0), 0).toLocaleString('id-ID');
                        }

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
    });
</script>
@endpush
@endsection

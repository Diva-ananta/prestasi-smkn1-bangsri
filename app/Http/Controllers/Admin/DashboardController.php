<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Prestasi;
use App\Models\DetailPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalSiswa = Siswa::count();
        $totalPrestasi = Prestasi::count();
        $totalSiswaBerprestasi = DetailPrestasi::distinct('siswa_id')->count('siswa_id');

        $chart = $this->buildChartPayload($request);
        $rangeType = $chart['rangeType'];
        $startDate = $chart['startDate'];
        $endDate = $chart['endDate'];
        $chartLabels = $chart['chartLabels'];
        $chartData = $chart['chartData'];

        $prestasiTerbaru = Prestasi::with('detailPrestasi.siswa')
            ->latest()
            ->take(5)
            ->get();

        $topSiswa = DetailPrestasi::with([
            'siswa' => fn ($query) => $query->select('id', 'nama', 'foto', 'kelas', 'jurusan', 'public_token')
        ])
            ->select('siswa_id', DB::raw('count(*) as total'))
            ->groupBy('siswa_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $websiteViews = (int) Cache::get('public_website_views', 0);

        // === Data tambahan untuk insight ===
        $siswaAktif = Siswa::where('status', 'Aktif')->count();
        $siswaAlumni = max($totalSiswa - $siswaAktif, 0);

        $prestasiPublish = Prestasi::where('status', 'Publish')->count();
        $prestasiDraft = max($totalPrestasi - $prestasiPublish, 0);

        $prestasiByTingkat = Prestasi::query()
            ->whereNotNull('tingkat')
            ->where('tingkat', '!=', '')
            ->select('tingkat', DB::raw('count(*) as total'))
            ->groupBy('tingkat')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $maxTingkat = $prestasiByTingkat->max('total') ?: 1;

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalPrestasi',
            'totalSiswaBerprestasi',
            'chartLabels',
            'chartData',
            'rangeType',
            'startDate',
            'endDate',
            'prestasiTerbaru',
            'topSiswa',
            'websiteViews',
            'siswaAktif',
            'siswaAlumni',
            'prestasiPublish',
            'prestasiDraft',
            'prestasiByTingkat',
            'maxTingkat'
        ));
    }

    public function chart(Request $request)
    {
        return response()->json($this->buildChartPayload($request));
    }

    private function buildChartPayload(Request $request): array
    {
        $rangeType = $request->query('range_type', 'year');
        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->subYear()->startOfDay();
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        if ($rangeType === 'all') {
            $minPrestasiDate = Prestasi::whereNotNull('tanggal_mulai')->min('tanggal_mulai');
            $maxPrestasiDate = Prestasi::whereNotNull('tanggal_mulai')->max('tanggal_mulai');

            $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : ($minPrestasiDate ? \Carbon\Carbon::parse($minPrestasiDate)->startOfDay() : now()->startOfYear());
            $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : ($maxPrestasiDate ? \Carbon\Carbon::parse($maxPrestasiDate)->endOfDay() : now()->endOfDay());
        }

        $chartLabels = [];
        $chartData = [];

        if ($rangeType === 'all') {
            $yearSeries = Prestasi::query()
                ->whereNotNull('tanggal_mulai')
                ->when($request->filled('start_date'), fn ($query) => $query->where('tanggal_mulai', '>=', $startDate))
                ->when($request->filled('end_date'), fn ($query) => $query->where('tanggal_mulai', '<=', $endDate))
                ->selectRaw('YEAR(tanggal_mulai) as tahun, COUNT(*) as total')
                ->groupByRaw('YEAR(tanggal_mulai)')
                ->orderBy('tahun')
                ->get();

            foreach ($yearSeries as $item) {
                $chartLabels[] = 'Tahun ' . $item->tahun;
                $chartData[] = (int) $item->total;
            }

            if (empty($chartLabels)) {
                $chartLabels = ['Tahun ' . now()->year];
                $chartData = [0];
            }
        } else {
            $monthSeries = Prestasi::query()
                ->whereNotNull('tanggal_mulai')
                ->where('tanggal_mulai', '>=', $startDate)
                ->where('tanggal_mulai', '<=', $endDate)
                ->selectRaw('YEAR(tanggal_mulai) as tahun, MONTH(tanggal_mulai) as bulan, COUNT(*) as total')
                ->groupByRaw('YEAR(tanggal_mulai), MONTH(tanggal_mulai)')
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get();

            $period = collect();
            $cursor = $startDate->copy()->startOfMonth();
            $last = $endDate->copy()->startOfMonth();

            while ($cursor->lte($last)) {
                $period->push($cursor->copy());
                $cursor->addMonth();
            }

            foreach ($period as $bulan) {
                $match = $monthSeries->first(function ($item) use ($bulan) {
                    return (int) $item->tahun === (int) $bulan->year && (int) $item->bulan === (int) $bulan->month;
                });

                $chartLabels[] = $bulan->translatedFormat('M Y');
                $chartData[] = $match ? (int) $match->total : 0;
            }

            if (empty($chartLabels)) {
                $chartLabels = [$startDate->translatedFormat('M Y')];
                $chartData = [0];
            }
        }

        return [
            'rangeType' => $rangeType,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'labelText' => $rangeType === 'all' ? 'Semua tahun' : 'Periode 1 tahun',
        ];
    }
}
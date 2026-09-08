<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Prestasi;
use App\Models\DetailPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalPrestasi = Prestasi::count();
        $totalPrestasiTim = Prestasi::where('jenis_peserta', 'Tim')->count();
        $totalSiswaBerprestasi = DetailPrestasi::distinct('siswa_id')->count('siswa_id');

        $mulaiPeriode = now()->startOfMonth()->subMonths(5);
        $trenPrestasi = Prestasi::query()
            ->where('created_at', '>=', $mulaiPeriode)
            ->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn ($item) => $item->tahun . '-' . $item->bulan);

        $bulanLabels = [];
        $bulanData = [];
        for ($i = 0; $i < 6; $i++) {
            $bulan = $mulaiPeriode->copy()->addMonths($i);
            $bulanLabels[] = $bulan->translatedFormat('M');
            $bulanData[] = (int) ($trenPrestasi[$bulan->year . '-' . $bulan->month]->total ?? 0);
        }

        $prestasiTerbaru = Prestasi::with('detailPrestasi.siswa')
            ->latest()
            ->take(5)
            ->get();

        $topSiswa = DetailPrestasi::with('siswa:id,nama,kelas,jurusan')
            ->select('siswa_id', DB::raw('count(*) as total'))
            ->groupBy('siswa_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalPrestasi',
            'totalPrestasiTim',
            'totalSiswaBerprestasi',
            'bulanLabels',
            'bulanData',
            'prestasiTerbaru',
            'topSiswa'
        ));
    }
}
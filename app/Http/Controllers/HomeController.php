<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Prestasi;
use App\Models\DetailPrestasi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   public function index()
{
    $totalSiswa = Siswa::count();
    $totalSiswaAktif = Siswa::count();
    $totalPrestasi = Prestasi::count();

    // Ganti nama menjadi $tingkatNasional dan $tingkatProvinsi
    $tingkatNasional = Prestasi::where('tingkat', 'Nasional')->count();

    $tingkatProvinsi = Prestasi::where('tingkat', 'Provinsi')->count();

    // Prestasi terbaru
    $prestasiTerbaru = Prestasi::with('detailPrestasi.siswa')
        ->latest()
        ->limit(5)
        ->get();

    return view('home', compact(
        'totalSiswa',
        'totalSiswaAktif',
        'totalPrestasi',
        'tingkatNasional',
        'tingkatProvinsi',
        'prestasiTerbaru'
    ));
}
}
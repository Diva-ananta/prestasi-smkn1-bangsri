<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Services\SiPintuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiPintuController extends Controller
{
    public function __construct(
        protected SiPintuService $sipintu
    ) {}

    /**
     * Dashboard Manajemen Integrasi SiPintu Gateway
     */
    public function index(): View
    {
        $pingResult = $this->sipintu->ping();
        $totalSiswaAktif = Siswa::where('status', 'Aktif')->count();
        $totalAlumni = Siswa::where('status', 'Alumni')->count();
        $totalSiswaLokal = $totalSiswaAktif;

        $config = [
            'base_url' => $this->sipintu->getBaseUrl(),
            'client_id' => $this->sipintu->getClientId(),
            'redirect_uri' => $this->sipintu->getRedirectUri(),
        ];

        // Ambil data siswa aktif terbaru dari database lokal
        $recentStudents = Siswa::where('status', 'Aktif')->latest()->take(20)->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'nis' => $s->nis,
                'nisn' => $s->nisn,
                'nama' => $s->nama,
                'name' => $s->nama,
                'jk' => $s->jenis_kelamin,
                'kelas' => $s->kelas . ($s->jurusan && $s->jurusan !== '-' ? ' ' . $s->jurusan : ''),
                'classroom' => ['name' => $s->kelas . ($s->jurusan && $s->jurusan !== '-' ? ' ' . $s->jurusan : '')],
                'status' => 'Aktif',
                'source' => 'Database Lokal (Siswa Aktif)',
            ];
        });

        // Ambil data alumni terbaru dari database lokal
        $recentAlumni = Siswa::where('status', 'Alumni')->latest()->take(20)->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'nis' => $s->nis,
                'nisn' => $s->nisn,
                'nama' => $s->nama,
                'name' => $s->nama,
                'jk' => $s->jenis_kelamin,
                'kelas' => 'Alumni ' . ($s->tahun_lulus ?? $s->angkatan ?? ''),
                'classroom' => null,
                'status' => 'Alumni',
                'source' => 'Database Lokal (Alumni)',
            ];
        });

        return view('admin.sipintu.index', compact(
            'pingResult',
            'totalSiswaLokal',
            'totalSiswaAktif',
            'totalAlumni',
            'config',
            'recentStudents',
            'recentAlumni'
        ));
    }

    /**
     * AJAX endpoint untuk uji koneksi live (Heartbeat Ping)
     */
    public function ping(): JsonResponse
    {
        $result = $this->sipintu->ping();
        return response()->json($result);
    }

    /**
     * AJAX endpoint untuk uji validasi kredensial (Validate Client)
     */
    public function validateClient(): JsonResponse
    {
        $result = $this->sipintu->validateClient();
        return response()->json($result);
    }

    /**
     * Jalankan sinkronisasi data siswa dari SiPintu Gateway ke database lokal
     */
    public function syncStudents(Request $request): JsonResponse|RedirectResponse
    {
        $limit = $request->input('limit') ? (int) $request->input('limit') : null;
        $type = $request->input('type', 'siswa'); // 'siswa', 'alumni', 'all'
        $result = $this->sipintu->syncStudentsToLocalDatabase($limit, $type);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * AJAX pencarian data siswa/alumni langsung ke SiPintu API
     */
    public function searchStudents(Request $request): JsonResponse
    {
        $nis = $request->input('nis');
        $search = $request->input('search');
        $type = $request->input('type', 'siswa'); // 'siswa', 'alumni', 'all'

        $result = $this->sipintu->getStudents($nis, $search, 20, $type);
        return response()->json($result);
    }

    /**
     * AJAX pencarian data guru langsung ke SiPintu API
     */
    public function searchTeachers(Request $request): JsonResponse
    {
        $nip = $request->input('nip');
        $search = $request->input('search');

        $result = $this->sipintu->getTeachers($nip, $search);
        return response()->json($result);
    }
}

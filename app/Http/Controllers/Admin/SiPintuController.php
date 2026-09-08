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
        $totalSiswaLokal = Siswa::count();

        $config = [
            'base_url' => $this->sipintu->getBaseUrl(),
            'client_id' => $this->sipintu->getClientId(),
            'redirect_uri' => $this->sipintu->getRedirectUri(),
        ];

        return view('admin.sipintu.index', compact('pingResult', 'totalSiswaLokal', 'config'));
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
        $result = $this->sipintu->syncStudentsToLocalDatabase($limit);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * AJAX pencarian data siswa langsung ke SiPintu API
     */
    public function searchStudents(Request $request): JsonResponse
    {
        $nis = $request->input('nis');
        $search = $request->input('search');

        $result = $this->sipintu->getStudents($nis, $search, 15);
        return response()->json($result);
    }
}

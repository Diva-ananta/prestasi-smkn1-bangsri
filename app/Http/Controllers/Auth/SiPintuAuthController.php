<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SiPintuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SiPintuAuthController extends Controller
{
    public function __construct(
        protected SiPintuService $sipintu
    ) {}

    /**
     * Redirect browser user ke halaman otorisasi login SiPintu SSO
     */
    public function redirect(Request $request): RedirectResponse
    {
        $state = Str::random(40);
        $request->session()->put('sipintu_oauth_state', $state);

        $authUrl = $this->sipintu->getAuthorizationUrl($state);

        return redirect()->away($authUrl);
    }

    /**
     * Tangkap redirect callback dari SiPintu Gateway membawa ?code=...&state=...
     */
    public function callback(Request $request): RedirectResponse
    {
        // 1. Cek jika pengguna membatalkan atau terjadi error dari gateway
        if ($request->has('error')) {
            $errorDesc = $request->input('error_description', $request->input('error', 'Otorisasi login dibatalkan oleh pengguna atau server.'));
            return redirect()->route('login')->with('error', "Login SiPintu dibatalkan: {$errorDesc}");
        }

        // 2. Validasi State Token CSRF
        $savedState = $request->session()->pull('sipintu_oauth_state');
        $receivedState = (string) $request->input('state');

        if (empty($savedState) || empty($receivedState) || !hash_equals($savedState, $receivedState)) {
            return redirect()->route('login')->with('error', 'Validasi sesi keamanan (state token) kedaluwarsa atau tidak valid. Silakan coba login kembali.');
        }

        // 3. Validasi Authorization Code
        $code = (string) $request->input('code');
        if (empty($code)) {
            return redirect()->route('login')->with('error', 'Kode otorisasi dari SiPintu Gateway tidak ditemukan.');
        }

        // 4. Tukar Code dengan Access Token
        $tokenResult = $this->sipintu->getAccessToken($code);
        if (!$tokenResult['success']) {
            Log::warning('SiPintu SSO Token Exchange Failed', ['result' => $tokenResult]);
            return redirect()->route('login')->with('error', 'Gagal memverifikasi token ke SiPintu Gateway: ' . ($tokenResult['message'] ?? 'Unknown error'));
        }

        $accessToken = $tokenResult['data']['access_token'] ?? null;
        if (empty($accessToken)) {
            return redirect()->route('login')->with('error', 'Token akses yang diterima dari SiPintu Gateway kosong.');
        }

        // 5. Ambil data profil user via endpoint /api/v1/user
        $profileResult = $this->sipintu->getUserProfile($accessToken);
        if (!$profileResult['success']) {
            Log::warning('SiPintu SSO Fetch Profile Failed', ['result' => $profileResult]);
            return redirect()->route('login')->with('error', 'Gagal mengambil data profil dari SiPintu: ' . ($profileResult['message'] ?? 'Unknown error'));
        }

        $userProfile = $profileResult['data'] ?? [];

        try {
            // 6. Sinkronkan atau buat data user lokal
            $user = $this->sipintu->handleSsoUser($userProfile);

            // 7. Login ke aplikasi
            Auth::login($user, true);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang, {$user->name}! Anda berhasil masuk melalui SiPintu Identity Gateway.");
        } catch (\Throwable $e) {
            Log::error('SiPintu SSO handle user error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat memproses data akun pengguna: ' . $e->getMessage());
        }
    }
}

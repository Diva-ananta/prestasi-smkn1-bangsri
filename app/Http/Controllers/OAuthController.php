<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    /**
     * Menerima otorisasi SSO otomatis dari Portal SiPintu Gateway
     */
    public function callback(Request $request)
    {
        // 1. Tangkap Authorization Code yang dikirim SiPintu
        $code = $request->input('code');

        if (! $code) {
            return redirect()->route('login')->with('error', 'Otorisasi SSO SiPintu gagal: Kode otorisasi tidak ditemukan.');
        }

        $baseUrl      = rtrim(env('SIPINTU_BASE_URL', config('services.sipintu.base_url', 'https://sipintu.smkn1bangsri.sch.id')), '/');
        $clientId     = env('SIPINTU_CLIENT_ID', config('services.sipintu.client_id'));
        $clientSecret = env('SIPINTU_CLIENT_SECRET', config('services.sipintu.client_secret'));
        $redirectUri  = env('SIPINTU_REDIRECT_URI', config('services.sipintu.redirect_uri'));

        // 2. Tukar Code dengan Access Token (Backend-to-Backend HTTP POST)
        $tokenResponse = Http::asForm()->acceptJson()->post("{$baseUrl}/oauth/token", [
            'grant_type'    => 'authorization_code',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        ]);

        if ($tokenResponse->failed()) {
            $errorMsg = $tokenResponse->json('error_description') ?? $tokenResponse->json('message') ?? 'Gagal memverifikasi token ke SiPintu Gateway.';
            return redirect()->route('login')->with('error', $errorMsg);
        }

        $accessToken = $tokenResponse->json('access_token');

        // 3. Ambil data profil pengguna dari SiPintu Gateway
        $userResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->get("{$baseUrl}/api/v1/user");

        if ($userResponse->failed()) {
            return redirect()->route('login')->with('error', 'Gagal mengambil data akun dari SiPintu Gateway.');
        }

        $sipintuUser = $userResponse->json('data') ?? $userResponse->json();

        // 4. Auto-Provisioning & Pemetaan User Lokal
        // Akun otomatis dibuat jika belum ada, atau diupdate jika sudah ada
        $user = User::updateOrCreate(
            ['email' => $sipintuUser['email']],
            [
                'name'              => $sipintuUser['name'] ?? $sipintuUser['nama'] ?? 'Pengguna SiPintu',
                'role'              => $sipintuUser['role'] ?? 'admin',
                'is_admin'          => true,
                'sipintu_id'        => (string) ($sipintuUser['id'] ?? $sipintuUser['sub'] ?? ''),
                'sipintu_data'      => $sipintuUser,
                // Sinkronisasi password hash dari SiPintu sesuai kebijakan integrasi
                'password'          => $sipintuUser['password'] ?? bcrypt(Str::random(24)),
                'email_verified_at' => now(),
            ]
        );

        // Pastikan hash password lokal selalu sinkron jika user memperbarui password di SiPintu
        if (isset($sipintuUser['password']) && $user->password !== $sipintuUser['password']) {
            $user->update(['password' => $sipintuUser['password']]);
        }

        // 5. Loginkan pengguna ke sesi lokal aplikasi
        Auth::login($user, true);
        $request->session()->regenerate();

        // 6. Langsung arahkan ke Dashboard (Tanpa melihat form login!)
        return redirect()->intended(route('admin.dashboard'))->with('success', "Selamat datang kembali, {$user->name}!");
    }
}

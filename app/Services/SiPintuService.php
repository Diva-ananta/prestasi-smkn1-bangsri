<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SiPintuService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.sipintu.base_url', env('SIPINTU_BASE_URL', 'https://sipintu.smkn1bangsri.sch.id')), '/');
        $this->clientId = (string) config('services.sipintu.client_id', env('SIPINTU_CLIENT_ID', 'app_mecmvhpduc8e'));
        $this->clientSecret = (string) config('services.sipintu.client_secret', env('SIPINTU_CLIENT_SECRET', 'sec_uEr8wGucp1jda8Ls6qOBsW03HrYVj6UK'));
        $this->redirectUri = (string) config('services.sipintu.redirect_uri', env('SIPINTU_REDIRECT_URI', 'http://localhost:8000/oauth/callback'));
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * HTTP Client dasar dengan autentikasi header SiPintu Gateway
     */
    public function client()
    {
        return Http::withHeaders([
            'X-Client-ID'     => $this->clientId,
            'X-Client-Secret' => $this->clientSecret,
            'Accept'          => 'application/json',
        ])->connectTimeout(10)->timeout(25);
    }

    /**
     * Uji konektivitas gateway & connection heartbeat (GET /api/v1/ping)
     */
    public function ping(): array
    {
        $start = microtime(true);
        try {
            $response = Http::connectTimeout(10)
                ->timeout(20)
                ->retry(2, 500)
                ->acceptJson()
                ->get("{$this->baseUrl}/api/v1/ping", [
                    'client_id' => $this->clientId,
                ]);

            $latency = round((microtime(true) - $start) * 1000, 2);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'latency_ms' => $latency,
                    'status_code' => $response->status(),
                    'data' => $data,
                    'message' => $data['message'] ?? 'Koneksi ke SiPintu Gateway aktif dan online.',
                ];
            }

            return [
                'success' => false,
                'latency_ms' => $latency,
                'status_code' => $response->status(),
                'error' => "Gateway merespons status HTTP {$response->status()}",
                'message' => $response->body(),
            ];
        } catch (\Throwable $e) {
            $latency = round((microtime(true) - $start) * 1000, 2);
            return [
                'success' => false,
                'latency_ms' => $latency,
                'status_code' => null,
                'error' => $e->getMessage(),
                'message' => 'Gagal terhubung ke SiPintu Gateway: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validasi kredensial client_id dan client_secret (POST /api/v1/validate-client)
     */
    public function validateClient(): array
    {
        try {
            $response = Http::connectTimeout(10)
                ->timeout(20)
                ->retry(2, 500)
                ->acceptJson()
                ->asJson()
                ->post("{$this->baseUrl}/api/v1/validate-client", [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => $data['valid'] ?? true,
                    'data' => $data,
                    'message' => $data['message'] ?? 'Kredensial SiPintu Gateway terverifikasi.',
                ];
            }

            return [
                'success' => false,
                'status_code' => $response->status(),
                'message' => 'Validasi kredensial gagal: ' . ($response->json('message') ?? $response->body()),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Gagal memvalidasi kredensial: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Ambil data siswa SIJUNA via Server-to-Server Gateway (Header Auth)
     * GET /api/v1/sijuna/students
     */
    public function getStudents(?string $nis = null, ?string $search = null, ?int $limit = null): array
    {
        try {
            $params = [];
            if (!empty($nis)) {
                $params['nis'] = $nis;
            }
            if (!empty($search)) {
                $params['search'] = $search;
            }
            if (!empty($limit)) {
                $params['limit'] = $limit;
            }

            $response = Http::connectTimeout(15)
                ->timeout(45)
                ->retry(2, 1000)
                ->withHeaders([
                    'X-Client-ID'     => $this->clientId,
                    'X-Client-Secret' => $this->clientSecret,
                    'Accept'          => 'application/json',
                ])
                ->get("{$this->baseUrl}/api/v1/sijuna/students", $params);

            if ($response->successful()) {
                $json = $response->json();
                return [
                    'success' => true,
                    'data' => $json['data'] ?? (is_array($json) ? $json : []),
                    'total' => $json['count'] ?? count($json['data'] ?? []),
                    'source' => $json['source'] ?? 'SiPintu Gateway',
                ];
            }

            return [
                'success' => false,
                'status_code' => $response->status(),
                'message' => 'Gagal mengambil data siswa: ' . ($response->json('message') ?? "HTTP {$response->status()}"),
                'data' => [],
            ];
        } catch (\Throwable $e) {
            Log::error('SiPintu getStudents error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Koneksi error saat mengambil data siswa: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Ambil data guru SIJUNA via Server-to-Server Gateway (Header Auth)
     * GET /api/v1/sijuna/teachers
     */
    public function getTeachers(?string $nip = null, ?string $search = null): array
    {
        try {
            $params = [];
            if (!empty($nip)) {
                $params['nip'] = $nip;
            }
            if (!empty($search)) {
                $params['search'] = $search;
            }

            $response = Http::timeout(20)
                ->withHeaders([
                    'X-Client-ID'     => $this->clientId,
                    'X-Client-Secret' => $this->clientSecret,
                    'Accept'          => 'application/json',
                ])
                ->get("{$this->baseUrl}/api/v1/sijuna/teachers", $params);

            if ($response->successful()) {
                $json = $response->json();
                return [
                    'success' => true,
                    'data' => $json['data'] ?? (is_array($json) ? $json : []),
                    'total' => $json['count'] ?? count($json['data'] ?? []),
                ];
            }

            return [
                'success' => false,
                'status_code' => $response->status(),
                'message' => 'Gagal mengambil data guru: ' . ($response->json('message') ?? "HTTP {$response->status()}"),
                'data' => [],
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Koneksi error: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Sinkronisasi data siswa dari SiPintu Gateway ke database lokal Siswa
     */
    public function syncStudentsToLocalDatabase(?int $limit = null): array
    {
        @set_time_limit(300);

        $fetchResult = $this->getStudents(limit: $limit);
        if (!$fetchResult['success'] || empty($fetchResult['data'])) {
            return [
                'success' => false,
                'message' => $fetchResult['message'] ?? 'Tidak ada data siswa yang diterima dari SiPintu Gateway.',
                'created' => 0,
                'updated' => 0,
                'total' => 0,
            ];
        }

        $students = $fetchResult['data'];
        if ($limit && $limit > 0 && count($students) > $limit) {
            $students = array_slice($students, 0, $limit);
        }
        $created = 0;
        $updated = 0;
        $skipped = 0;

        DB::transaction(function () use ($students, &$created, &$updated, &$skipped) {
            foreach ($students as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $nis = trim((string) ($item['nis'] ?? ''));
                if (empty($nis)) {
                    $skipped++;
                    continue;
                }

                $nama = trim((string) ($item['nama'] ?? $item['name'] ?? ''));
                if (empty($nama)) {
                    $skipped++;
                    continue;
                }

                // Normalisasi jenis kelamin
                $rawJk = $item['jk'] ?? $item['jenis_kelamin'] ?? null;
                $jk = 'L';
                if ($rawJk == 2 || strtoupper((string) $rawJk) === 'P' || stripos((string) $rawJk, 'perempuan') !== false) {
                    $jk = 'P';
                }

                // Normalisasi NISN
                $nisn = !empty($item['nisn']) ? trim((string) $item['nisn']) : null;

                // Ekstraksi kelas & jurusan
                $classroomName = $item['classroom']['name'] ?? $item['classroom_name'] ?? $item['kelas'] ?? '';
                $parsedClass = $this->parseClassroom($classroomName);

                // Angkatan perkiraan
                $angkatan = $item['angkatan'] ?? null;
                if (!$angkatan && !empty($item['created_at'])) {
                    $angkatan = (int) date('Y', strtotime($item['created_at']));
                }
                if (!$angkatan || $angkatan < 2000) {
                    $angkatan = (int) date('Y');
                }

                $siswa = Siswa::where('nis', $nis)->first();

                $payload = [
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'jenis_kelamin' => $jk,
                    'kelas' => $parsedClass['kelas'],
                    'jurusan' => $parsedClass['jurusan'],
                    'angkatan' => $angkatan,
                    'status' => 'Aktif',
                    'is_published' => true,
                ];

                if ($siswa) {
                    $siswa->update($payload);
                    $updated++;
                } else {
                    Siswa::create($payload);
                    $created++;
                }
            }
        });

        return [
            'success' => true,
            'message' => "Sinkronisasi berhasil: {$created} siswa baru ditambahkan, {$updated} siswa diperbarui.",
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'total' => count($students),
        ];
    }

    /**
     * Parse nama kelas menjadi tingkat kelas (10/11/12 atau X/XI/XII) dan jurusan (PPLG, TO, dsb)
     */
    protected function parseClassroom(string $classroomName): array
    {
        $classroom = trim(strtoupper($classroomName));

        // Default
        $kelas = '10';
        $jurusan = 'PPLG';

        if (preg_match('/\b(XII|12)\b/i', $classroom)) {
            $kelas = '12';
        } elseif (preg_match('/\b(XI|11)\b/i', $classroom)) {
            $kelas = '11';
        } elseif (preg_match('/\b(X|10)\b/i', $classroom)) {
            $kelas = '10';
        }

        if (str_contains($classroom, 'PPLG') || str_contains($classroom, 'RPL')) {
            $jurusan = 'PPLG';
        } elseif (str_contains($classroom, 'TO') || str_contains($classroom, 'TKRO') || str_contains($classroom, 'TBSM')) {
            $jurusan = 'TO';
        } elseif (str_contains($classroom, 'AKL') || str_contains($classroom, 'AKUNTANSI')) {
            $jurusan = 'AKL';
        } elseif (str_contains($classroom, 'MPLB') || str_contains($classroom, 'OTKP') || str_contains($classroom, 'AP')) {
            $jurusan = 'MPLB';
        } elseif (str_contains($classroom, 'PM') || str_contains($classroom, 'BD') || str_contains($classroom, 'BDP') || str_contains($classroom, 'BISNIS')) {
            $jurusan = 'PM';
        }

        return [
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ];
    }

    // =========================================================================
    // OAUTH 2.0 / OPENID CONNECT SSO (USER AUTH)
    // =========================================================================

    /**
     * Langkah 1: Buat URL redirect untuk otorisasi SSO pengguna
     */
    public function getAuthorizationUrl(string $state): string
    {
        $query = http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'state'         => $state,
            'scope'         => 'openid profile email',
        ]);

        return "{$this->baseUrl}/oauth/authorize?{$query}";
    }

    /**
     * Langkah 3: Tukar authorization code sementara dengan access token di backend
     */
    public function getAccessToken(string $code): array
    {
        try {
            $response = Http::asForm()
                ->timeout(15)
                ->post("{$this->baseUrl}/oauth/token", [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code'          => $code,
                    'redirect_uri'  => $this->redirectUri,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'status_code' => $response->status(),
                'message' => 'Gagal menukar token: ' . ($response->json('error_description') ?? $response->json('message') ?? $response->body()),
            ];
        } catch (\Throwable $e) {
            Log::error('SiPintu getAccessToken error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Koneksi gagal saat menukar token: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Langkah 4: Ambil profil user yang sedang login via Bearer access_token
     */
    public function getUserProfile(string $accessToken): array
    {
        try {
            $response = Http::withToken($accessToken)
                ->timeout(15)
                ->acceptJson()
                ->get("{$this->baseUrl}/api/v1/user");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'status_code' => $response->status(),
                'message' => 'Gagal mengambil profil user: ' . ($response->json('message') ?? "HTTP {$response->status()}"),
            ];
        } catch (\Throwable $e) {
            Log::error('SiPintu getUserProfile error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Koneksi gagal saat mengambil profil user: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Temukan atau daftarkan user lokal berdasarkan profil SiPintu SSO
     */
    public function handleSsoUser(array $profile): User
    {
        // Profil mungkin dibungkus ['data'] atau langsung di root
        $userProfile = $profile['data'] ?? $profile;

        $sipintuId = (string) ($userProfile['id'] ?? $userProfile['sub'] ?? '');
        $email = $userProfile['email'] ?? null;
        $name = $userProfile['name'] ?? $userProfile['nama'] ?? 'Pengguna SiPintu';

        // Jika email kosong, generate dari sipintuId
        if (empty($email)) {
            $email = "sipintu_{$sipintuId}@smkn1bangsri.sch.id";
        }

        // Cari user yang sudah ada berdasarkan sipintu_id atau email
        $user = null;
        if (!empty($sipintuId)) {
            $user = User::where('sipintu_id', $sipintuId)->first();
        }

        if (!$user && !empty($email)) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            // Update data terbaru
            $user->update([
                'name' => $name,
                'sipintu_id' => $sipintuId,
                'sipintu_data' => $userProfile,
                'is_admin' => true,
            ]);
            return $user;
        }

        // Buat user baru jika belum ada
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::random(32)),
            'sipintu_id' => $sipintuId,
            'sipintu_data' => $userProfile,
            'role' => 'admin',
            'is_admin' => true,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SiPintuService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SiPintuIntegrationTest extends TestCase
{
    public function test_login_page_renders_cleanly_without_sipintu_sso_button(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('Masuk dengan SiPintu SSO');
        $response->assertSee('Masuk ke Dashboard');
    }

    public function test_oauth_redirect_to_sipintu_gateway(): void
    {
        $response = $this->get('/oauth/sipintu');

        $response->assertStatus(302);
        $location = $response->headers->get('Location');
        $this->assertStringContainsString('https://sipintu.smkn1bangsri.sch.id/oauth/authorize', $location);
        $this->assertStringContainsString('client_id=app_mecmvhpduc8e', $location);
        $this->assertStringContainsString('redirect_uri=', $location);
        $this->assertStringContainsString('state=', $location);
    }

    public function test_admin_sipintu_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin/sipintu');
        $response->assertRedirect('/login');
    }

    public function test_admin_sipintu_dashboard_accessible_by_admin(): void
    {
        $user = User::first() ?? User::factory()->create([
            'is_admin' => true,
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/admin/sipintu');

        $response->assertStatus(200);
        $response->assertSee('Pusat Integrasi SiPintu');
        $response->assertSee('app_mecmvhpduc8e');
        $response->assertSee('Sinkronisasi Siswa SIJUNA');
    }

    public function test_sipintu_service_credentials_configured(): void
    {
        $service = app(SiPintuService::class);

        $this->assertEquals('https://sipintu.smkn1bangsri.sch.id', $service->getBaseUrl());
        $this->assertEquals('app_mecmvhpduc8e', $service->getClientId());
    }

    public function test_oauth_callback_handles_successful_user_sso(): void
    {
        Http::fake([
            '*/oauth/token' => Http::response([
                'access_token' => 'mock_access_token_xyz',
                'token_type' => 'Bearer',
                'expires_in' => 86400,
            ], 200),
            '*/api/v1/user' => Http::response([
                'id' => 101,
                'name' => 'Guru Penguji SSO',
                'email' => 'penguji.sso@smkn1bangsri.sch.id',
                'role' => 'guru',
            ], 200),
        ]);

        $state = 'test_random_state_token_12345';

        $response = $this->withSession(['sipintu_oauth_state' => $state])
            ->get("/oauth/callback?code=mock_auth_code&state={$state}");

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'penguji.sso@smkn1bangsri.sch.id')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Guru Penguji SSO', $user->name);
        $this->assertEquals('101', $user->sipintu_id);
    }

    public function test_portal_initiated_sso_callback_without_session_state(): void
    {
        Http::fake([
            '*/oauth/token' => Http::response([
                'access_token' => 'mock_token_portal_init',
                'token_type' => 'Bearer',
            ], 200),
            '*/api/v1/user' => Http::response([
                'id' => 102,
                'name' => 'Siswa Dari Portal SiPintu',
                'email' => 'siswa.portal@smkn1bangsri.sch.id',
                'role' => 'siswa',
            ], 200),
        ]);

        $response = $this->get('/oauth/callback?code=portal_auth_code');

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'siswa.portal@smkn1bangsri.sch.id')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Siswa Dari Portal SiPintu', $user->name);
    }

    public function test_admin_can_search_teachers_from_gateway(): void
    {
        $admin = User::first() ?? User::factory()->create(['is_admin' => true, 'role' => 'admin']);

        Http::fake([
            '*/api/v1/sijuna/teachers*' => Http::response([
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'nip' => '199301162022211008',
                        'kode' => 'AW',
                        'nama' => 'Iwan Safrudin',
                        'jk' => 1,
                        'hp' => '085758700025',
                    ]
                ]
            ], 200),
        ]);

        $response = $this->actingAs($admin)->getJson('/admin/sipintu/search-teachers?search=Iwan');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                [
                    'nip' => '199301162022211008',
                    'nama' => 'Iwan Safrudin',
                ]
            ]
        ]);
    }

    public function test_sync_students_separates_active_students_and_alumni(): void
    {
        $admin = User::first() ?? User::factory()->create(['is_admin' => true, 'role' => 'admin']);

        Http::fake([
            '*/api/v1/sijuna/students*' => Http::response([
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'nis' => 'TEST_AKTIF_01',
                        'nama' => 'Siswa Aktif Test',
                        'classroom' => ['name' => 'XII PPLG 1'],
                        'jk' => 'L',
                        'angkatan' => 2024,
                    ],
                    [
                        'id' => 2,
                        'nis' => 'TEST_ALUMNI_01',
                        'nama' => 'Alumni Test',
                        'classroom' => null,
                        'jk' => 'P',
                        'angkatan' => 2021,
                    ],
                ]
            ], 200),
        ]);

        // Sync siswa aktif saja
        $responseSiswa = $this->actingAs($admin)->postJson('/admin/sipintu/sync-students', ['type' => 'siswa']);
        $responseSiswa->assertStatus(200);
        $this->assertDatabaseHas('siswa', [
            'nis' => 'TEST_AKTIF_01',
            'status' => 'Aktif',
            'kelas' => '12',
        ]);
        $this->assertDatabaseMissing('siswa', [
            'nis' => 'TEST_ALUMNI_01',
        ]);

        // Sync alumni saja
        $responseAlumni = $this->actingAs($admin)->postJson('/admin/sipintu/sync-students', ['type' => 'alumni']);
        $responseAlumni->assertStatus(200);
        $this->assertDatabaseHas('siswa', [
            'nis' => 'TEST_ALUMNI_01',
            'status' => 'Alumni',
            'kelas' => 'Alumni',
        ]);
    }

    public function test_admin_siswa_index_filters_by_status(): void
    {
        $admin = User::first() ?? User::factory()->create(['is_admin' => true, 'role' => 'admin']);

        \App\Models\Siswa::updateOrCreate(['nis' => 'FILT_AKTIF'], [
            'nama' => 'Siswa Aktif Filter',
            'jenis_kelamin' => 'L',
            'kelas' => '11',
            'jurusan' => 'PPLG',
            'angkatan' => 2024,
            'status' => 'Aktif',
            'is_published' => true,
        ]);

        \App\Models\Siswa::updateOrCreate(['nis' => 'FILT_ALUMNI'], [
            'nama' => 'Alumni Filter',
            'jenis_kelamin' => 'P',
            'kelas' => 'Alumni',
            'jurusan' => '-',
            'angkatan' => 2020,
            'status' => 'Alumni',
            'tahun_lulus' => 2023,
            'is_published' => true,
        ]);

        $resAktif = $this->actingAs($admin)->get('/admin/siswa?status=Aktif');
        $resAktif->assertStatus(200);
        $resAktif->assertSee('Siswa Aktif Filter');
        $resAktif->assertDontSee('Alumni Filter');

        $resAlumni = $this->actingAs($admin)->get('/admin/siswa?status=Alumni');
        $resAlumni->assertStatus(200);
        $resAlumni->assertSee('Alumni Filter');
        $resAlumni->assertDontSee('Siswa Aktif Filter');
    }
}


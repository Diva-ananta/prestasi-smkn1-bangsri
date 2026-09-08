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
}

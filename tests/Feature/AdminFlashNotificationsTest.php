<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminFlashNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_layout_displays_session_flash_messages(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->withSession(['success' => 'Siswa berhasil ditambahkan.'])
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Siswa berhasil ditambahkan.');
    }

    public function test_login_redirect_sets_clear_success_message(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@smk.test',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertEquals('Login berhasil. Selamat datang di dashboard admin.', session('success'));
    }
}

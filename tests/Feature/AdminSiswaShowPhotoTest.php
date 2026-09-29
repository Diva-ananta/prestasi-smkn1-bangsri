<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSiswaShowPhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_siswa_show_displays_uploaded_photo(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'role' => 'master_admin',
        ]);

        $siswa = Siswa::create([
            'nis' => '12345',
            'nisn' => '1234567890',
            'nama' => 'Budi Santoso',
            'foto' => 'foto-siswa/budi.jpg',
            'jenis_kelamin' => 'L',
            'kelas' => '12',
            'jurusan' => 'PPLG',
            'angkatan' => 2024,
            'status' => 'Aktif',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.siswa.show', $siswa));

        $response->assertOk();
        $response->assertSee('src="' . asset('storage/' . $siswa->foto) . '"', false);
    }

    public function test_student_filters_render_matching_results_for_ajax_updates(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'master_admin']);
        Siswa::create([
            'nis' => '24001',
            'nama' => 'Siswa Cocok',
            'jenis_kelamin' => 'L',
            'kelas' => '12',
            'jurusan' => 'PPLG',
            'angkatan' => 2024,
            'status' => 'Aktif',
        ]);
        Siswa::create([
            'nis' => '23002',
            'nama' => 'Siswa Tidak Cocok',
            'jenis_kelamin' => 'P',
            'kelas' => '11',
            'jurusan' => 'MPLB',
            'angkatan' => 2023,
            'status' => 'Alumni',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.siswa.index', [
            'search' => 'Siswa',
            'status' => 'Aktif',
            'kelas' => '12',
            'jurusan' => 'PPLG',
            'angkatan' => 2024,
        ]), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertOk()
            ->assertSee('Siswa Cocok')
            ->assertDontSee('Siswa Tidak Cocok')
            ->assertSee('id="siswa-results"', false);
    }
}

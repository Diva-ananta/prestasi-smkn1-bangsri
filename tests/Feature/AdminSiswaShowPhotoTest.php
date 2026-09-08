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
}

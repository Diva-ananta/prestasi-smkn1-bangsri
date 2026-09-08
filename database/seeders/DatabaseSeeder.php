<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Utama SMKN 1 Bangsri
        User::updateOrCreate(
            ['email' => 'admin@smkn1bangsri.sch.id'],
            [
                'name' => 'Administrator SMKN 1 Bangsri',
                'password' => Hash::make('admin123'),
                'role' => 'master_admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Akun Fallback / Pengujian
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password'),
                'role' => 'master_admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {--email= : Alamat email administrator}
                            {--name= : Nama lengkap administrator}
                            {--password= : Password akun}
                            {--role=master_admin : Role akun (master_admin atau admin)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat akun administrator baru atau perbarui password akun admin yang sudah ada';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('===========================================================');
        $this->info('           PEMBUATAN AKUN ADMINISTRATOR');
        $this->info('===========================================================');

        $email = $this->option('email');
        if (! $email) {
            $email = $this->input->isInteractive()
                ? $this->ask('Masukkan alamat email admin', 'admin@smkn1bangsri.sch.id')
                : 'admin@smkn1bangsri.sch.id';
        }

        $name = $this->option('name');
        if (! $name) {
            $name = ($this->input->isInteractive() && ! $this->option('email'))
                ? $this->ask('Masukkan nama lengkap admin', 'Administrator SMKN 1 Bangsri')
                : 'Administrator SMKN 1 Bangsri';
        }

        $password = $this->option('password');
        if (! $password) {
            $password = ($this->input->isInteractive() && ! $this->option('email'))
                ? $this->secret('Masukkan password admin (tekan enter untuk "admin123")') ?: 'admin123'
                : 'admin123';
        }

        $role = $this->option('role') ?: 'master_admin';

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'              => $name,
                'password'          => Hash::make($password),
                'role'              => $role,
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );

        $this->newLine();
        $this->info("✓ Akun administrator berhasil disimpan!");
        $this->table(
            ['Parameter', 'Nilai'],
            [
                ['Nama', $user->name],
                ['Email', $user->email],
                ['Password', $password],
                ['Role', $user->role],
                ['is_admin', $user->is_admin ? 'true (1)' : 'false (0)'],
            ]
        );

        $this->line("Silakan login di: <comment>" . url('/login') . "</comment>");
        $this->newLine();

        return self::SUCCESS;
    }
}

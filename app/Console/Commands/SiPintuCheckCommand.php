<?php

namespace App\Console\Commands;

use App\Services\SiPintuService;
use Illuminate\Console\Command;

class SiPintuCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sipintu:check {--sync : Jalankan sinkronisasi data siswa sekaligus} {--limit= : Batasi jumlah siswa yang disinkronkan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uji koneksi, periksa kesehatan API, dan validasi kredensial SiPintu Gateway';

    /**
     * Execute the console command.
     */
    public function handle(SiPintuService $sipintu): int
    {
        $this->info('===========================================================');
        $this->info('  SIPINTU IDENTITY & API GATEWAY - CONNECTION TEST');
        $this->info('===========================================================');

        $baseUrl = $sipintu->getBaseUrl();
        $clientId = $sipintu->getClientId();
        $redirectUri = $sipintu->getRedirectUri();

        $this->line("• Base URL      : <comment>{$baseUrl}</comment>");
        $this->line("• Client ID     : <comment>{$clientId}</comment>");
        $this->line("• Redirect URI  : <comment>{$redirectUri}</comment>");
        $this->newLine();

        // 1. Tes Ping / Heartbeat
        $this->info('1. Menguji Koneksi Ping / Heartbeat...');
        $ping = $sipintu->ping();
        if ($ping['success']) {
            $this->info("   [OK] Gateway Online ({$ping['latency_ms']} ms)");
            if (isset($ping['data']['client_connection'])) {
                $conn = $ping['data']['client_connection'];
                $this->line("        - Aplikasi : " . ($conn['name'] ?? '-'));
                $this->line("        - Status   : " . ($conn['status'] ?? '-'));
            }
        } else {
            $this->error("   [FAILED] " . ($ping['message'] ?? $ping['error'] ?? 'Unknown error'));
            return Command::FAILURE;
        }

        $this->newLine();

        // 2. Validasi Kredensial Client ID & Secret
        $this->info('2. Memvalidasi Kredensial Client & Secret...');
        $validation = $sipintu->validateClient();
        if ($validation['success']) {
            $this->info('   [OK] Kredensial Valid & Terverifikasi.');
            if (isset($validation['data']['application'])) {
                $app = $validation['data']['application'];
                $this->line("        - Nama Terdaftar : " . ($app['name'] ?? '-'));
                $this->line("        - Total Request  : " . ($app['total_api_requests'] ?? 0));
            }
        } else {
            $this->error("   [FAILED] " . ($validation['message'] ?? 'Validasi gagal'));
            return Command::FAILURE;
        }

        $this->newLine();

        // 3. Tes Pengambilan Data Siswa SIJUNA (Server-to-Server)
        $this->info('3. Menguji Server-to-Server Header Auth (Data Siswa)...');
        $studentsRes = $sipintu->getStudents(limit: 2);
        if ($studentsRes['success']) {
            $count = $studentsRes['total'];
            $sample = $studentsRes['data'][0] ?? null;
            $sampleName = $sample ? ($sample['nama'] ?? $sample['name'] ?? '-') : '-';
            $sampleNis = $sample ? ($sample['nis'] ?? '-') : '-';
            $this->info("   [OK] Berhasil terhubung ke endpoint siswa! (Total siswa di SIJUNA: {$count})");
            $this->line("        - Sampel data: {$sampleNis} - {$sampleName}");
        } else {
            $this->warn("   [WARNING] " . ($studentsRes['message'] ?? 'Gagal mengambil sampel siswa'));
        }

        $this->newLine();

        // 4. Jika ada flag --sync
        if ($this->option('sync')) {
            $this->info('4. Menjalankan Sinkronisasi Siswa ke Database Lokal...');
            $limit = $this->option('limit') ? (int) $this->option('limit') : null;
            $syncRes = $sipintu->syncStudentsToLocalDatabase($limit);

            if ($syncRes['success']) {
                $this->info("   [OK] {$syncRes['message']}");
            } else {
                $this->error("   [FAILED] {$syncRes['message']}");
            }
            $this->newLine();
        }

        $this->info('===========================================================');
        $this->info('  HASIL: Integrasi SiPintu Gateway Berhasil dan Siap Dipakai!');
        $this->info('===========================================================');

        return Command::SUCCESS;
    }
}

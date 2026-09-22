<?php

namespace App\Console\Commands;

use App\Models\ApiClient;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateApiClient extends Command
{
    protected $signature = 'api-client:create {name}';

    protected $description = 'Membuat API client baru untuk SiPres';

    public function handle(): int
    {
        $name = $this->argument('name');

        $key = 'sipres_' . Str::random(56);

        $client = ApiClient::create([
            'name' => $name,
            'key' => $key,
            'is_active' => true,
        ]);

        $this->newLine();

        $this->info('API Client berhasil dibuat.');

        $this->line('Nama : ' . $client->name);
        $this->line('Key  : ' . $client->key);

        $this->newLine();

        $this->warn('Simpan API key ini di tempat aman.');
        $this->warn('Key tidak ditampilkan ulang oleh sistem.');

        return self::SUCCESS;
    }
}
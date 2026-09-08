<?php

namespace App\Imports;

use App\Models\Prestasi;
use App\Models\DetailPrestasi;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrestasiImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (Prestasi::where('nama_lomba', $row['nama_lomba'])
                ->where('hasil', $row['hasil'])
                ->whereDate('tanggal_mulai', $row['tanggal_mulai'] ?? null)
                ->exists()) {
                throw ValidationException::withMessages(['file' => "Prestasi {$row['nama_lomba']} dengan hasil {$row['hasil']} sudah terdaftar."]);
            }

            $prestasi = Prestasi::create([
                'nama_lomba' => $row['nama_lomba'],
                'penyelenggara' => $row['penyelenggara'] ?? null,
                'hasil' => $row['hasil'],
                'tingkat' => $row['tingkat'] ?? null,
                'kategori' => $row['kategori'] ?? null,
                'lokasi' => $row['lokasi'] ?? null,
                'tanggal_mulai' => $row['tanggal_mulai'] ?? null,
                'jenis_peserta' => $row['jenis_peserta'],
                'nama_tim' => $row['nama_tim'] ?? null,
                'status' => $row['status'] ?? 'Draft',
                'keterangan' => $row['keterangan'] ?? null,
                'foto' => $this->resolveFoto($row['foto'] ?? null),
            ]);

            foreach (preg_split('/\s*,\s*/', (string) $row['siswa']) as $studentKey) {
                $siswa = Siswa::where('nis', $studentKey)->first() ?? Siswa::where('nama', $studentKey)->first();
                if (!$siswa) {
                    throw ValidationException::withMessages(['file' => "Siswa {$studentKey} tidak ditemukan."]);
                }
                DetailPrestasi::create(['prestasi_id' => $prestasi->id, 'siswa_id' => $siswa->id, 'peran' => 'Anggota']);
            }
        }
    }

    private function resolveFoto(?string $foto): ?string
    {
        $foto = trim((string) $foto);
        if ($foto === '') return null;

        if (filter_var($foto, FILTER_VALIDATE_URL)) {
            $applicationUrl = rtrim((string) config('app.url'), '/');
            if ($applicationUrl !== '' && str_starts_with($foto, $applicationUrl . '/storage/')) {
                $localPath = ltrim(substr($foto, strlen($applicationUrl . '/storage/')), '/');
                return Storage::disk('public')->exists($localPath) ? $localPath : null;
            }

            $response = Http::timeout(15)->get($foto);
            $contentType = strtolower((string) $response->header('Content-Type'));
            if ($response->successful() && str_starts_with($contentType, 'image/')) {
                $extension = match (true) {
                    str_contains($contentType, 'png') => 'png',
                    str_contains($contentType, 'webp') => 'webp',
                    str_contains($contentType, 'gif') => 'gif',
                    default => 'jpg',
                };
                $filename = Str::uuid() . '.' . strtolower($extension);
                return Storage::disk('public')->put('foto-prestasi/' . $filename, $response->body())
                    ? 'foto-prestasi/' . $filename
                    : null;
            }
            return null;
        }

        $path = ltrim(str_replace(['storage/', 'public/'], '', $foto), '/');
        return Storage::disk('public')->exists($path) ? $path : null;
    }

    public function rules(): array
    {
        return [
            'nama_lomba' => 'required|string',
            'hasil' => 'required|string',
            'jenis_peserta' => 'required|in:Individu,Tim',
            'siswa' => 'required|string',
        ];
    }

    public function headingRow(): int
    {
        return 1; // Baris pertama adalah header
    }
}
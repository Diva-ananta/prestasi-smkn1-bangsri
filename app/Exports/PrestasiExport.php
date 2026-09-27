<?php

namespace App\Exports;

use App\Models\Prestasi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrestasiExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private readonly array $ids = [],
        private readonly ?string $search = null,
        private readonly bool $all = false,
        private readonly array $exceptIds = [],
    ) {}

    public function query()
    {
        return Prestasi::query()
            ->with('siswa')
            ->when(!$this->all && $this->ids, fn ($query) => $query->whereIn('id', $this->ids))
            ->when($this->all && $this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nama_lomba', 'like', "%{$this->search}%")
                        ->orWhere('hasil', 'like', "%{$this->search}%")
                        ->orWhere('kategori', 'like', "%{$this->search}%")
                        ->orWhere('tingkat', 'like', "%{$this->search}%");
                });
            })
            ->when($this->all && $this->exceptIds, fn ($query) => $query->whereNotIn('id', $this->exceptIds))
            ->latest();
    }

    public function headings(): array { return ['nama_lomba', 'penyelenggara', 'hasil', 'tingkat', 'kategori', 'lokasi', 'tanggal_mulai', 'jenis_peserta', 'nama_tim', 'siswa', 'foto', 'status', 'keterangan']; }
    public function map($prestasi): array { return [$prestasi->nama_lomba, $prestasi->penyelenggara, $prestasi->hasil, $prestasi->tingkat, $prestasi->kategori, $prestasi->lokasi, $prestasi->tanggal_mulai?->format('Y-m-d'), $prestasi->jenis_peserta, $prestasi->nama_tim, $prestasi->siswa->pluck('nama')->implode(', '), $prestasi->foto ? asset('storage/' . $prestasi->foto) : null, $prestasi->status, $prestasi->keterangan]; }
}

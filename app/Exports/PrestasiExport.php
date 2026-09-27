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
        private readonly bool $all = false,
        private readonly array $filters = [],
    ) {}

    public function query()
    {
        return Prestasi::query()
            ->with('siswa')
            ->when(!$this->all && $this->ids, fn ($query) => $query->whereIn('id', $this->ids))
            ->when($this->all, fn ($query) => $query
                ->when($this->filters['search'] ?? null, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('nama_lomba', 'like', "%{$search}%")
                            ->orWhere('hasil', 'like', "%{$search}%")
                            ->orWhere('kategori', 'like', "%{$search}%")
                            ->orWhere('tingkat', 'like', "%{$search}%");
                    });
                })
                ->when($this->filters['tahun'] ?? null, fn ($query, $tahun) => $query->whereYear('tanggal_mulai', $tahun))
                ->when($this->filters['kategori'] ?? null, fn ($query, $kategori) => $query->where('kategori', $kategori))
                ->when($this->filters['tingkat'] ?? null, fn ($query, $tingkat) => $query->where('tingkat', $tingkat))
                ->when($this->filters['jenis_peserta'] ?? null, fn ($query, $jenis) => $query->where('jenis_peserta', $jenis))
                ->when($this->filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status)))
            ->latest();
    }

    public function headings(): array { return ['nama_lomba', 'penyelenggara', 'hasil', 'tingkat', 'kategori', 'lokasi', 'tanggal_mulai', 'jenis_peserta', 'nama_tim', 'siswa', 'foto', 'status', 'keterangan']; }
    public function map($prestasi): array { return [$prestasi->nama_lomba, $prestasi->penyelenggara, $prestasi->hasil, $prestasi->tingkat, $prestasi->kategori, $prestasi->lokasi, $prestasi->tanggal_mulai?->format('Y-m-d'), $prestasi->jenis_peserta, $prestasi->nama_tim, $prestasi->siswa->pluck('nama')->implode(', '), $prestasi->foto ? asset('storage/' . $prestasi->foto) : null, $prestasi->status, $prestasi->keterangan]; }
}

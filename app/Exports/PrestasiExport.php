<?php

namespace App\Exports;

use App\Models\Prestasi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrestasiExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private readonly array $ids = []) {}
    public function query() { return Prestasi::query()->with('siswa')->when($this->ids, fn ($query) => $query->whereIn('id', $this->ids))->latest(); }
    public function headings(): array { return ['nama_lomba', 'penyelenggara', 'hasil', 'tingkat', 'kategori', 'lokasi', 'tanggal_mulai', 'jenis_peserta', 'nama_tim', 'siswa', 'foto', 'status', 'keterangan']; }
    public function map($prestasi): array { return [$prestasi->nama_lomba, $prestasi->penyelenggara, $prestasi->hasil, $prestasi->tingkat, $prestasi->kategori, $prestasi->lokasi, $prestasi->tanggal_mulai?->format('Y-m-d'), $prestasi->jenis_peserta, $prestasi->nama_tim, $prestasi->siswa->pluck('nama')->implode(', '), $prestasi->foto ? asset('storage/' . $prestasi->foto) : null, $prestasi->status, $prestasi->keterangan]; }
}

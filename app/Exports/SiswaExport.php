<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private readonly array $ids = []) {}
    public function query() { return Siswa::query()->when($this->ids, fn ($query) => $query->whereIn('id', $this->ids))->orderBy('nama'); }
    public function headings(): array { return ['nis', 'nisn', 'nama', 'foto', 'jenis_kelamin', 'kelas', 'jurusan', 'angkatan']; }
    public function map($siswa): array { return [$siswa->nis, $siswa->nisn, $siswa->nama, $siswa->foto, $siswa->jenis_kelamin, $siswa->kelas, $siswa->jurusan, $siswa->angkatan]; }
}

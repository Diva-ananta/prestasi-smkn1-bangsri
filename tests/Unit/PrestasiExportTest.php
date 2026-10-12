<?php

namespace Tests\Unit;

use App\Exports\PrestasiExport;
use App\Models\Prestasi;
use App\Models\Siswa;
use Tests\TestCase;

class PrestasiExportTest extends TestCase
{
    public function test_export_includes_each_student_name_and_nis(): void
    {
        $prestasi = new Prestasi([
            'nama_lomba' => 'Lomba Test',
            'hasil' => 'Juara 1',
            'jenis_peserta' => 'Tim',
        ]);
        $prestasi->setRelation('siswa', collect([
            new Siswa(['nama' => 'Saka', 'nis' => '4687']),
            new Siswa(['nama' => 'Budi', 'nis' => '1234']),
        ]));

        $row = (new PrestasiExport)->map($prestasi);

        $this->assertSame('Saka(4687), Budi(1234)', $row[9]);
    }

    public function test_export_query_applies_all_active_filters(): void
    {
        $export = new PrestasiExport([], true, [
            'search' => 'olimpiade',
            'tahun' => 2025,
            'kategori' => 'Sains',
            'tingkat' => 'Kabupaten',
            'jenis_peserta' => 'Tim',
            'status' => 'Publish',
        ]);

        $query = $export->query();

        $this->assertSame([
            '%olimpiade%',
            '%olimpiade%',
            '%olimpiade%',
            '%olimpiade%',
            2025,
            'Sains',
            'Kabupaten',
            'Tim',
            'Publish',
        ], $query->getBindings());
    }
}

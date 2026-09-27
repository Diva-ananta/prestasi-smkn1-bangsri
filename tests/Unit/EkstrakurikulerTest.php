<?php

namespace Tests\Unit;

use App\Helpers\Ekstrakurikuler;
use Tests\TestCase;

class EkstrakurikulerTest extends TestCase
{
    public function test_pramuka_names_resolve_to_the_pramuka_site(): void
    {
        $expected = 'https://pramuka.smkn1bangsri.sch.id/';

        $this->assertSame($expected, Ekstrakurikuler::url('Pramuka'));
        $this->assertSame($expected, Ekstrakurikuler::url('pramuka smk negeri 1 bangsri'));
        $this->assertSame($expected, Ekstrakurikuler::url('  PRAMUKA  '));
    }

    public function test_configured_extracurricular_urls_are_trimmed(): void
    {
        $this->assertSame(
            'https://smkn1bangsri.sch.id/extracurriculars/',
            Ekstrakurikuler::url('Palawa Futsal Skansaba'),
        );
    }
}
<?php

namespace Tests\Feature;

use App\Models\Prestasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PublicHomeAchievementOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_latest_cards_and_slides_follow_achievement_date_not_input_date(): void
    {
        $newerAchievement = Prestasi::create([
            'nama_lomba' => 'Prestasi tanggal terbaru',
            'jenis_peserta' => 'Individu',
            'hasil' => 'Juara 1',
            'tanggal_mulai' => '2026-09-10',
            'foto' => 'foto-prestasi/newer.webp',
            'status' => 'Publish',
        ]);
        $newerAchievement->forceFill(['created_at' => Carbon::parse('2026-08-01')])->save();

        $olderAchievement = Prestasi::create([
            'nama_lomba' => 'Prestasi tanggal lama',
            'jenis_peserta' => 'Individu',
            'hasil' => 'Juara 2',
            'tanggal_mulai' => '2026-02-10',
            'foto' => 'foto-prestasi/older.webp',
            'status' => 'Publish',
        ]);
        $olderAchievement->forceFill(['created_at' => Carbon::parse('2026-09-20')])->save();

        $response = $this->get(route('home'));

        $response->assertOk();
        $content = $response->getContent();
        $newerPosition = strpos($content, 'Prestasi tanggal terbaru');
        $olderPosition = strpos($content, 'Prestasi tanggal lama');

        $this->assertNotFalse($newerPosition);
        $this->assertNotFalse($olderPosition);
        $this->assertLessThan($olderPosition, $newerPosition);
    }
}

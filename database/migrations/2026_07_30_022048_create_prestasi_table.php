<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('prestasi', function (Blueprint $table) {
        $table->id();
        $table->string('nama_lomba');
        $table->string('kategori')->nullable();
        $table->string('tingkat')->nullable();
        $table->string('penyelenggara')->nullable();
        $table->date('tanggal_mulai')->nullable();
        $table->date('tanggal_selesai')->nullable();
        $table->string('lokasi')->nullable();
        $table->string('bidang_lomba')->nullable();
        $table->enum('jenis_peserta', ['Individu', 'Tim']);
        $table->string('nama_tim')->nullable();
        $table->string('hasil');
        $table->string('kategori_juara')->nullable();
        $table->string('sertifikat')->nullable();
        $table->string('foto')->nullable();
        $table->enum('status', ['Draft', 'Publish'])->default('Draft');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('prestasi');
}
};
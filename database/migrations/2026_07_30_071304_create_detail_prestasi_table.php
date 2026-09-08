<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestasi_id')->constrained('prestasi')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('peran')->default('Anggota');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_prestasi');
    }
};
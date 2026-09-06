<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('artikel')) {
            Schema::create('artikel', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->string('slug')->unique();
                $table->text('isi');
                $table->string('gambar')->nullable();
                $table->string('penulis')->nullable();
                $table->date('tanggal_publikasi')->nullable();
                $table->enum('status', ['Draft', 'Publish'])->default('Draft');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
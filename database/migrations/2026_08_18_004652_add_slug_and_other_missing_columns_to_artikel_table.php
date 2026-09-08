<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            if (!Schema::hasColumn('artikel', 'judul')) $table->string('judul')->after('id');
            if (!Schema::hasColumn('artikel', 'slug')) $table->string('slug')->unique()->after('judul');
            if (!Schema::hasColumn('artikel', 'isi')) $table->text('isi')->after('slug');
            if (!Schema::hasColumn('artikel', 'gambar')) $table->string('gambar')->nullable()->after('isi');
            if (!Schema::hasColumn('artikel', 'penulis')) $table->string('penulis')->nullable()->after('gambar');
            if (!Schema::hasColumn('artikel', 'tanggal_publikasi')) $table->date('tanggal_publikasi')->nullable()->after('penulis');
            if (!Schema::hasColumn('artikel', 'status')) $table->enum('status', ['Draft', 'Publish'])->default('Draft')->after('tanggal_publikasi');
        });
    }

    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            foreach (['judul', 'slug', 'isi', 'gambar', 'penulis', 'tanggal_publikasi', 'status'] as $col) {
                if (Schema::hasColumn('artikel', $col)) $table->dropColumn($col);
            }
        });
    }
};
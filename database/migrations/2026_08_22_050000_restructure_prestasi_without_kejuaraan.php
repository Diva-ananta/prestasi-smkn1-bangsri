<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('prestasi')) {
            Schema::table('prestasi', function (Blueprint $table) {
                foreach (['nama_lomba', 'kategori', 'tingkat', 'penyelenggara', 'tanggal_mulai', 'tanggal_selesai', 'lokasi'] as $column) {
                    if (!Schema::hasColumn('prestasi', $column)) $table->{$column === 'tanggal_mulai' || $column === 'tanggal_selesai' ? 'date' : 'string'}($column)->nullable();
                }
            });
            if (Schema::hasColumn('prestasi', 'kejuaraan_id')) {
                Schema::table('prestasi', function (Blueprint $table) {
                    $table->dropForeign(['kejuaraan_id']);
                    $table->dropColumn('kejuaraan_id');
                });
            }
        }
        if (Schema::hasTable('artikel') && !Schema::hasColumn('artikel', 'prestasi_id')) {
            Schema::table('artikel', function (Blueprint $table) {
                $table->foreignId('prestasi_id')->nullable()->after('status')->constrained('prestasi')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('artikel') && Schema::hasColumn('artikel', 'prestasi_id')) {
            Schema::table('artikel', function (Blueprint $table) {
                $table->dropForeign(['prestasi_id']);
                $table->dropColumn('prestasi_id');
            });
        }
        if (Schema::hasTable('prestasi')) {
            Schema::table('prestasi', function (Blueprint $table) {
                $table->dropColumn(['nama_lomba', 'kategori', 'tingkat', 'penyelenggara', 'tanggal_mulai', 'tanggal_selesai', 'lokasi']);
            });
        }
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Alumni'])->default('Aktif')->after('angkatan');
            $table->year('tahun_lulus')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['status', 'tahun_lulus']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->foreignId('prestasi_id')->nullable()->after('id')->constrained('prestasi')->nullOnDelete();
            $table->boolean('is_published')->default(true)->after('foto');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->dropForeign(['prestasi_id']);
            $table->dropIndex(['is_published']);
            $table->dropColumn(['prestasi_id', 'is_published']);
        });
    }
};

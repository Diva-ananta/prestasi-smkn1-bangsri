<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('artikel', 'video_url')) {
            Schema::table('artikel', function (Blueprint $table) {
                $table->string('video_url', 2048)->nullable()->after('gambar');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('artikel', 'video_url')) {
            Schema::table('artikel', function (Blueprint $table) {
                $table->dropColumn('video_url');
            });
        }
    }
};

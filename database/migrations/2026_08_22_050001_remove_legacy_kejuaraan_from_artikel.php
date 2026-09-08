<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('artikel') && Schema::hasColumn('artikel', 'kejuaraan_id')) {
            Schema::table('artikel', function (Blueprint $table) {
                $table->dropForeign(['kejuaraan_id']);
                $table->dropColumn('kejuaraan_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('artikel') && !Schema::hasColumn('artikel', 'kejuaraan_id')) {
            Schema::table('artikel', function (Blueprint $table) {
                $table->unsignedBigInteger('kejuaraan_id')->nullable();
            });
        }
    }
};
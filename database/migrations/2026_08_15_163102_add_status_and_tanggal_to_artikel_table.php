<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            if (!Schema::hasColumn('artikel', 'tanggal_publikasi')) {
                $table->date('tanggal_publikasi')->nullable();
            }
            if (!Schema::hasColumn('artikel', 'status')) {
                $table->enum('status', ['Draft', 'Publish'])->default('Draft');
            }
        });
    }

    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            if (Schema::hasColumn('artikel', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('artikel', 'tanggal_publikasi')) {
                $table->dropColumn('tanggal_publikasi');
            }
        });
    }
};

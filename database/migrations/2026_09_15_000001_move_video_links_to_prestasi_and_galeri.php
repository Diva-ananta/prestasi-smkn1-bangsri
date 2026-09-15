<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('prestasi') && ! Schema::hasColumn('prestasi', 'video_url')) {
            Schema::table('prestasi', function (Blueprint $table) {
                $table->string('video_url', 2048)->nullable()->after('foto');
            });
        }

        if (Schema::hasTable('galeri')) {
            Schema::table('galeri', function (Blueprint $table) {
                if (! Schema::hasColumn('galeri', 'video_url')) {
                    $table->string('video_url', 2048)->nullable()->after('foto');
                }
            });

            if (Schema::hasColumn('galeri', 'foto')) {
                Schema::table('galeri', function (Blueprint $table) {
                    $table->string('foto')->nullable()->change();
                });
            }
        }

        if (Schema::hasTable('artikel') && Schema::hasColumn('artikel', 'video_url')) {
            Schema::table('artikel', function (Blueprint $table) {
                $table->dropColumn('video_url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('artikel') && ! Schema::hasColumn('artikel', 'video_url')) {
            Schema::table('artikel', function (Blueprint $table) {
                $table->string('video_url', 2048)->nullable()->after('gambar');
            });
        }

        if (Schema::hasTable('galeri')) {
            if (Schema::hasColumn('galeri', 'video_url')) {
                Schema::table('galeri', function (Blueprint $table) {
                    $table->dropColumn('video_url');
                });
            }

            if (Schema::hasColumn('galeri', 'foto')) {
                Schema::table('galeri', function (Blueprint $table) {
                    $table->string('foto')->nullable(false)->change();
                });
            }
        }

        if (Schema::hasTable('prestasi') && Schema::hasColumn('prestasi', 'video_url')) {
            Schema::table('prestasi', function (Blueprint $table) {
                $table->dropColumn('video_url');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', fn (Blueprint $table) => $table->uuid('public_token')->nullable()->unique()->after('id'));
        Schema::table('prestasi', fn (Blueprint $table) => $table->uuid('public_token')->nullable()->unique()->after('id'));
        DB::table('siswa')->whereNull('public_token')->orderBy('id')->eachById(fn ($row) => DB::table('siswa')->where('id', $row->id)->update(['public_token' => (string) Str::uuid()]));
        DB::table('prestasi')->whereNull('public_token')->orderBy('id')->eachById(fn ($row) => DB::table('prestasi')->where('id', $row->id)->update(['public_token' => (string) Str::uuid()]));
    }

    public function down(): void
    {
        Schema::table('prestasi', fn (Blueprint $table) => $table->dropColumn('public_token'));
        Schema::table('siswa', fn (Blueprint $table) => $table->dropColumn('public_token'));
    }
};

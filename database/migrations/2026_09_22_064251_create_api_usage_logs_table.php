<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_usage_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('api_client_id')
                ->nullable()
                ->constrained('api_clients')
                ->nullOnDelete();

            $table->string('method', 10);
            $table->string('endpoint');
            $table->unsignedSmallInteger('status_code')->nullable();

            $table->ipAddress('ip_address')->nullable();

            $table->text('user_agent')->nullable();

            $table->unsignedInteger('response_time_ms')->nullable();

            $table->timestamps();

            $table->index(['api_client_id', 'created_at']);
            $table->index(['endpoint', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_usage_logs');
    }
};
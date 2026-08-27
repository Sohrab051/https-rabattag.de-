<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('awin_sync_runs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->unsignedInteger('merchants_created')->default(0);
            $table->unsignedInteger('merchants_updated')->default(0);
            $table->unsignedInteger('offers_created')->default(0);
            $table->unsignedInteger('offers_updated')->default(0);
            $table->unsignedInteger('offers_skipped')->default(0);
            $table->unsignedInteger('errors_count')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('awin_sync_runs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('click_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('click_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['merchant_id', 'created_at']);
            $table->index(['ip_address', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('click_logs');
    }
};

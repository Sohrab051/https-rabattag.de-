<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('click_log_id')->constrained()->cascadeOnDelete();
            $table->decimal('order_amount', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->string('external_reference')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};

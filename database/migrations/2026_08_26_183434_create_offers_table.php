<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $table->string('title_en');
            $table->string('title_de');
            $table->text('description_en')->nullable();
            $table->text('description_de')->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('min_purchase_amount', 10, 2)->nullable();
            $table->text('terms_en')->nullable();
            $table->text('terms_de')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['draft', 'pending', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

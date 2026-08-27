<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_de');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description_en')->nullable();
            $table->text('description_de')->nullable();
            $table->string('website_url')->nullable();
            $table->string('affiliate_link')->nullable();
            $table->decimal('commission_rate', 6, 2)->default(0);
            $table->string('api_provider')->nullable();
            $table->text('api_credentials')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending_contract'])->default('pending_contract');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};

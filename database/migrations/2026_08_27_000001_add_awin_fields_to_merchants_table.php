<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->string('awin_merchant_id')->nullable()->unique()->after('api_credentials');
            $table->enum('source', ['manual', 'awin'])->default('manual')->after('awin_merchant_id');
            $table->timestamp('last_synced_at')->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropColumn(['awin_merchant_id', 'source', 'last_synced_at']);
        });
    }
};

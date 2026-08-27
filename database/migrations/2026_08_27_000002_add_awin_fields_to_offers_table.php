<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('merchant_id')->constrained()->nullOnDelete();
            $table->string('coupon_code')->nullable()->after('status');
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage')->after('discount_value');
            $table->enum('deal_type', ['sale', 'coupon', 'cashback'])->default('sale')->after('discount_type');
            $table->string('affiliate_url')->nullable()->after('deal_type');
            $table->string('awin_deal_id')->nullable()->unique()->after('affiliate_url');
            $table->boolean('is_verified')->default(false)->after('awin_deal_id');
            $table->boolean('needs_review')->default(false)->after('is_verified');
            $table->enum('source', ['manual', 'awin'])->default('manual')->after('needs_review');
            $table->timestamp('synced_at')->nullable()->after('source');
        });

        $this->addExpiredStatus();
    }

    /**
     * Extend the `status` enum to include 'expired', alongside draft/pending/published.
     *
     * - MySQL: enums are a native column type, so we ALTER ... MODIFY COLUMN directly.
     * - SQLite: Laravel's enum() creates a plain varchar column with a CHECK constraint.
     *   SQLite can't ALTER a CHECK constraint in place, so we recreate the table with a
     *   widened constraint and copy the data across (documented intent: same enum values
     *   plus 'expired').
     */
    private function addExpiredStatus(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE offers MODIFY COLUMN status ENUM('draft', 'pending', 'published', 'expired') NOT NULL DEFAULT 'draft'");

            return;
        }

        if ($driver === 'sqlite') {
            DB::statement('CREATE TABLE offers_tmp AS SELECT * FROM offers');
            Schema::drop('offers');

            Schema::create('offers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
                $table->string('title_en');
                $table->string('title_de');
                $table->text('description_en')->nullable();
                $table->text('description_de')->nullable();
                $table->decimal('discount_value', 10, 2)->nullable();
                $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
                $table->enum('deal_type', ['sale', 'coupon', 'cashback'])->default('sale');
                $table->string('affiliate_url')->nullable();
                $table->string('awin_deal_id')->nullable()->unique();
                $table->boolean('is_verified')->default(false);
                $table->boolean('needs_review')->default(false);
                $table->enum('source', ['manual', 'awin'])->default('manual');
                $table->timestamp('synced_at')->nullable();
                $table->decimal('min_purchase_amount', 10, 2)->nullable();
                $table->string('coupon_code')->nullable();
                $table->text('terms_en')->nullable();
                $table->text('terms_de')->nullable();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->enum('status', ['draft', 'pending', 'published', 'expired'])->default('draft');
                $table->boolean('is_featured')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->unsignedInteger('priority')->default(0);
                $table->timestamps();

                $table->index(['status', 'published_at']);
            });

            $columns = implode(', ', [
                'id', 'merchant_id', 'category_id', 'title_en', 'title_de', 'description_en', 'description_de',
                'discount_value', 'discount_type', 'deal_type', 'affiliate_url', 'awin_deal_id', 'is_verified',
                'needs_review', 'source', 'synced_at', 'min_purchase_amount', 'coupon_code', 'terms_en', 'terms_de',
                'starts_at', 'expires_at', 'status', 'is_featured', 'published_at', 'priority', 'created_at', 'updated_at',
            ]);

            DB::statement("INSERT INTO offers ({$columns}) SELECT {$columns} FROM offers_tmp");
            Schema::drop('offers_tmp');

            return;
        }

        // Other drivers (e.g. pgsql): widen via a plain string check if needed.
        // Left undocumented here since this project targets mysql/sqlite only.
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn([
                'coupon_code', 'discount_type', 'deal_type', 'affiliate_url', 'awin_deal_id',
                'is_verified', 'needs_review', 'source', 'synced_at',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('type', 16); // percent | fixed
            $table->decimal('value', 14, 4);
            $table->decimal('min_subtotal', 14, 4)->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 16, 4)->default(0)->after('packaging_fee');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('track_stock')->default(false)->after('is_active');
            $table->decimal('stock_quantity', 14, 4)->nullable()->after('track_stock');
        });

        Schema::create('import_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('import_type', 64);
            $table->string('filename')->nullable();
            $table->boolean('dry_run')->default(false);
            $table->unsignedInteger('rows_total')->default(0);
            $table->unsignedInteger('rows_ok')->default(0);
            $table->unsignedInteger('rows_failed')->default(0);
            $table->json('row_errors')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_audit_logs');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['track_stock', 'stock_quantity']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropColumn('discount_amount');
        });

        Schema::dropIfExists('coupons');
    }
};

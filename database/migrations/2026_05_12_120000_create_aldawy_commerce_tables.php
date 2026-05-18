<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 32)->index();
            $table->string('code', 12);
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('slug');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('name');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->string('sku')->unique();
            $table->decimal('price_per_kg', 14, 4)->default(0);
            $table->decimal('price_per_piece', 14, 4)->nullable();
            $table->boolean('sell_by_piece')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('preparation_services', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->json('name');
            $table->decimal('surcharge_amount', 14, 4)->default(0);
            $table->boolean('surcharge_is_percent')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('packaging_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->json('name');
            $table->decimal('surcharge_amount', 14, 4)->default(0);
            $table->boolean('surcharge_is_percent')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('preparation_service_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('preparation_service_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_enabled')->default(true);
            $table->unique(['product_id', 'preparation_service_id'], 'prep_svc_product_unique');
        });

        Schema::create('packaging_type_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('packaging_type_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_enabled')->default(true);
            $table->unique(['product_id', 'packaging_type_id'], 'pkg_type_product_unique');
        });

        Schema::create('produce_boxes', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('slug');
            $table->decimal('price', 14, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('produce_box_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produce_box_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 14, 4);
            $table->string('unit', 16);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_phone', 32)->index();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('status', 32)->index();
            $table->string('payment_gateway', 64)->default('cod');
            $table->string('packaging_code', 64)->nullable();
            $table->decimal('subtotal', 16, 4)->default(0);
            $table->decimal('packaging_fee', 16, 4)->default(0);
            $table->decimal('total', 16, 4)->default(0);
            $table->string('invoice_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('produce_box_id')->nullable()->constrained()->nullOnDelete();
            $table->json('product_name_snapshot')->nullable();
            $table->string('unit', 16);
            $table->decimal('quantity', 16, 4);
            $table->json('services')->nullable();
            $table->string('packaging', 64);
            $table->decimal('unit_price', 14, 4)->default(0);
            $table->decimal('line_total', 16, 4)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produce_box_id')->constrained()->cascadeOnDelete();
            $table->string('interval', 16);
            $table->string('status', 32)->default('active')->index();
            $table->timestamp('starts_at');
            $table->timestamp('next_order_at')->index();
            $table->timestamp('last_generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('produce_box_items');
        Schema::dropIfExists('produce_boxes');
        Schema::dropIfExists('packaging_type_product');
        Schema::dropIfExists('preparation_service_product');
        Schema::dropIfExists('packaging_types');
        Schema::dropIfExists('preparation_services');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('phone_verifications');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->json('title');
            $table->json('subtitle');
            $table->json('badge_text')->nullable();
            $table->json('cta_label')->nullable();
            $table->string('cta_url', 2048)->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->string('gradient_from', 16)->nullable();
            $table->string('gradient_mid', 16)->nullable();
            $table->string('gradient_to', 16)->nullable();
            $table->text('hot_product_skus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_banners');
    }
};

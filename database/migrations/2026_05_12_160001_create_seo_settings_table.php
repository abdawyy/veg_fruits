<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('home_meta_title_en')->nullable();
            $table->string('home_meta_title_ar')->nullable();
            $table->text('home_meta_description_en')->nullable();
            $table->text('home_meta_description_ar')->nullable();
            $table->string('shop_meta_title_en')->nullable();
            $table->string('shop_meta_title_ar')->nullable();
            $table->text('shop_meta_description_en')->nullable();
            $table->text('shop_meta_description_ar')->nullable();
            $table->string('services_meta_title_en')->nullable();
            $table->string('services_meta_title_ar')->nullable();
            $table->text('services_meta_description_en')->nullable();
            $table->text('services_meta_description_ar')->nullable();
            $table->string('product_meta_title_suffix_en')->nullable();
            $table->string('product_meta_title_suffix_ar')->nullable();
            $table->string('og_image_url', 2048)->nullable();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('seo_settings')->insert([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};

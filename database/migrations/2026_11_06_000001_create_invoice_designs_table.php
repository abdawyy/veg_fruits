<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_designs', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->default('AL-DAWY');
            $table->json('tagline')->nullable();
            $table->string('phone', 64)->nullable();
            $table->string('email')->nullable();
            $table->json('address')->nullable();
            $table->string('accent_color', 7)->default('#16a34a');
            $table->string('header_color', 7)->default('#14532d');
            $table->json('thank_you')->nullable();
            $table->json('footer_note')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_designs');
    }
};

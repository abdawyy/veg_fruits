<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_strings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->text('value_en')->nullable();
            $table->text('value_ar')->nullable();
            $table->string('group', 64)->default('general')->index();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_strings');
    }
};

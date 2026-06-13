<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('session_id', 128);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('visited_at');
            $table->index(['product_id', 'visited_at']);
            $table->index(['product_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_views');
    }
};

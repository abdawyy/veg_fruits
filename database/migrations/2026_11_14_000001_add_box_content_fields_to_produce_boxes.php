<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produce_boxes', function (Blueprint $table) {
            $table->json('description')->nullable()->after('slug');
            $table->json('meta_title')->nullable()->after('description');
            $table->json('meta_description')->nullable()->after('meta_title');
            $table->string('image_path')->nullable()->after('meta_description');
            $table->decimal('compare_at_price', 14, 4)->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('produce_boxes', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'meta_title',
                'meta_description',
                'image_path',
                'compare_at_price',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_local')->default(true)->after('unit');
            $table->boolean('is_imported')->default(false)->after('is_local');
            $table->decimal('imported_price_per_kg', 14, 4)->nullable()->after('is_imported');
            $table->decimal('imported_price_per_gram', 14, 4)->nullable()->after('imported_price_per_kg');
            $table->decimal('imported_price_per_piece', 14, 4)->nullable()->after('imported_price_per_gram');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('origin', 16)->nullable()->after('unit');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('origin');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_local',
                'is_imported',
                'imported_price_per_kg',
                'imported_price_per_gram',
                'imported_price_per_piece',
            ]);
        });
    }
};

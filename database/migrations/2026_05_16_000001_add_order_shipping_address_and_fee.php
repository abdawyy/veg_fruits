<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_fee', 16, 4)->default(0)->after('packaging_fee');
            $table->string('shipping_address_line1')->nullable()->after('city_id');
            $table->string('shipping_address_line2')->nullable()->after('shipping_address_line1');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_fee', 'shipping_address_line1', 'shipping_address_line2']);
        });
    }
};

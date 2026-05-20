<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('default_city_id')->nullable()->after('is_admin')->constrained('cities')->nullOnDelete();
            $table->string('default_address_line1')->nullable()->after('default_city_id');
            $table->string('default_address_line2')->nullable()->after('default_address_line1');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('default_city_id');
            $table->dropColumn(['default_address_line1', 'default_address_line2']);
        });
    }
};

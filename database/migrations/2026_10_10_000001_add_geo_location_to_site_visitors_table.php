<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_visitors', function (Blueprint $table) {
            if (! Schema::hasColumn('site_visitors', 'country_code')) {
                $table->string('country_code', 2)->nullable()->after('device_type')->index();
            }
            if (! Schema::hasColumn('site_visitors', 'country')) {
                $table->string('country', 100)->nullable()->after('country_code');
            }
            if (! Schema::hasColumn('site_visitors', 'region')) {
                $table->string('region', 100)->nullable()->after('country');
            }
            if (! Schema::hasColumn('site_visitors', 'city')) {
                $table->string('city', 100)->nullable()->after('region');
            }
            if (! Schema::hasColumn('site_visitors', 'geo_resolved_at')) {
                $table->timestamp('geo_resolved_at')->nullable()->after('city');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_visitors', function (Blueprint $table) {
            $columns = array_values(array_filter(
                ['country_code', 'country', 'region', 'city', 'geo_resolved_at'],
                fn (string $column): bool => Schema::hasColumn('site_visitors', $column),
            ));

            if (in_array('country_code', $columns, true)) {
                $table->dropIndex(['country_code']);
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};

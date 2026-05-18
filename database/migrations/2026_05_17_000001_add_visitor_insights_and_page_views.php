<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_visitors', function (Blueprint $table) {
            if (! Schema::hasColumn('site_visitors', 'first_path')) {
                $table->string('first_path', 1024)->nullable()->after('last_path');
            }
            if (! Schema::hasColumn('site_visitors', 'referrer')) {
                $table->string('referrer', 2048)->nullable()->after('first_path');
            }
            if (! Schema::hasColumn('site_visitors', 'utm_source')) {
                $table->string('utm_source', 128)->nullable()->after('referrer');
            }
            if (! Schema::hasColumn('site_visitors', 'utm_medium')) {
                $table->string('utm_medium', 128)->nullable()->after('utm_source');
            }
            if (! Schema::hasColumn('site_visitors', 'utm_campaign')) {
                $table->string('utm_campaign', 128)->nullable()->after('utm_medium');
            }
            if (! Schema::hasColumn('site_visitors', 'device_type')) {
                $table->string('device_type', 32)->nullable()->after('utm_campaign');
            }
        });

        if (! Schema::hasTable('site_page_views')) {
            Schema::create('site_page_views', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 191)->index();
                $table->string('path', 512);
                $table->string('referrer', 1024)->nullable();
                $table->timestamp('visited_at')->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_page_views');

        Schema::table('site_visitors', function (Blueprint $table) {
            $cols = [
                'first_path',
                'referrer',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'device_type',
            ];
            $toDrop = array_values(array_filter($cols, fn (string $c): bool => Schema::hasColumn('site_visitors', $c)));
            if ($toDrop !== []) {
                $table->dropColumn($toDrop);
            }
        });
    }
};

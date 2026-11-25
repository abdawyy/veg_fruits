<?php

use App\Support\ProduceBoxSeoCopy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('produce_boxes')) {
            return;
        }

        ProduceBoxSeoCopy::applyToCatalogBoxes();
    }

    public function down(): void
    {
        // Box copy is catalog content. Rolling back does not restore empty descriptions.
    }
};

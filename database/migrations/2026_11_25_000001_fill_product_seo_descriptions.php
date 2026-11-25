<?php

use App\Support\ProduceSeoCopy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        ProduceSeoCopy::applyToCatalogProducts();
    }

    public function down(): void
    {
        // Product copy is catalog content. Rolling back does not restore the old one-line descriptions.
    }
};

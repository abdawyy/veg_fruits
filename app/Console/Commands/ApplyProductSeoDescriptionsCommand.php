<?php

namespace App\Console\Commands;

use App\Support\ProduceSeoCopy;
use Illuminate\Console\Command;

class ApplyProductSeoDescriptionsCommand extends Command
{
    protected $signature = 'aldawy:apply-product-seo-descriptions';

    protected $description = 'Write the bilingual SEO description onto each catalog product.';

    public function handle(): int
    {
        $updated = ProduceSeoCopy::applyToCatalogProducts();
        $this->info("Updated {$updated} product descriptions.");

        return self::SUCCESS;
    }
}

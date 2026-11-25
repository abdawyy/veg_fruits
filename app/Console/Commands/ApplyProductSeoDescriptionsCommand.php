<?php

namespace App\Console\Commands;

use App\Support\ProduceBoxSeoCopy;
use App\Support\ProduceSeoCopy;
use Illuminate\Console\Command;

class ApplyProductSeoDescriptionsCommand extends Command
{
    protected $signature = 'aldawy:apply-product-seo-descriptions';

    protected $description = 'Write the bilingual SEO description onto each catalog product and produce box.';

    public function handle(): int
    {
        $products = ProduceSeoCopy::applyToCatalogProducts();
        $boxes = ProduceBoxSeoCopy::applyToCatalogBoxes();
        $this->info("Updated {$products} product descriptions and {$boxes} produce box descriptions.");

        return self::SUCCESS;
    }
}

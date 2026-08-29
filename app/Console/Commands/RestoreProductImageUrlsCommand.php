<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Support\ProduceStockPhoto;
use Illuminate\Console\Command;

class RestoreProductImageUrlsCommand extends Command
{
    protected $signature = 'aldawy:restore-product-image-urls
                            {--sku= : Only process a single SKU}';

    protected $description = 'Restore external image URLs (Unsplash/Wikimedia) for products and clear local image_path.';

    public function handle(): int
    {
        $query = Product::query()->orderBy('sku');

        if ($sku = $this->option('sku')) {
            $query->where('sku', $sku);
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            $this->warn('No products matched.');

            return self::FAILURE;
        }

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $updated = 0;

        foreach ($products as $product) {
            $englishName = $product->getTranslation('name', 'en');

            $product->forceFill([
                'image_url' => ProduceStockPhoto::urlFor($englishName, $product->sku),
                'image_path' => null,
            ])->saveQuietly();

            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Restored internet image URLs for {$updated} products.");

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Support\ProduceStockPhoto;
use App\Support\ProductImageFetcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateProductImagesCommand extends Command
{
    protected $signature = 'aldawy:generate-product-images
                            {--force : Re-download even when a local image already exists}
                            {--sku= : Only process a single SKU}';

    protected $description = 'Download product photos by name and store them locally (replaces external image URLs).';

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

        $force = (bool) $this->option('force');
        $ok = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $englishName = $product->getTranslation('name', 'en');

            if (
                ! $force
                && $product->image_path
                && Storage::disk('public')->exists($product->image_path)
            ) {
                $skipped++;
                $bar->advance();

                continue;
            }

            if ($force && $product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            try {
                $path = ProductImageFetcher::fetchForProduct(
                    $product,
                    ProduceStockPhoto::candidateUrlsFor($englishName, $product->sku),
                );
            } catch (\Throwable $e) {
                $this->newLine();
                $this->error("Failed: {$product->sku} ({$englishName}) — {$e->getMessage()}");
                $failed++;
                $bar->advance();

                continue;
            }

            if ($path === null) {
                $this->newLine();
                $this->error("Failed: {$product->sku} ({$englishName})");
                $failed++;
                $bar->advance();

                continue;
            }

            $product->forceFill([
                'image_path' => $path,
                'image_url' => null,
            ])->saveQuietly();

            $ok++;
            $bar->advance();

            usleep(400_000);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Downloaded: {$ok} | Skipped (already local): {$skipped} | Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}

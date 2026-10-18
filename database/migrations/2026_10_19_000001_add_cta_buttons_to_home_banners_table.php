<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_banners', function (Blueprint $table) {
            $table->json('cta_buttons')->nullable()->after('cta_url');
        });

        foreach (DB::table('home_banners')->orderBy('id')->get(['id', 'cta_label', 'cta_url']) as $row) {
            $label = self::decodeJson($row->cta_label);
            $url = is_string($row->cta_url) ? trim($row->cta_url) : '';
            $labelEn = trim((string) ($label['en'] ?? ''));
            $labelAr = trim((string) ($label['ar'] ?? ''));

            if ($url === '' && $labelEn === '' && $labelAr === '') {
                continue;
            }

            DB::table('home_banners')->where('id', $row->id)->update([
                'cta_buttons' => json_encode([[
                    'label' => ['en' => $labelEn, 'ar' => $labelAr],
                    'url' => $url !== '' ? $url : null,
                    'product_id' => self::productIdFromUrl($url),
                ]], JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('home_banners', function (Blueprint $table) {
            $table->dropColumn('cta_buttons');
        });
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodeJson(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }

    private static function productIdFromUrl(string $url): ?int
    {
        if (preg_match('#/products/(\d+)(?:$|[/?])#', $url, $match) === 1) {
            return (int) $match[1];
        }

        return null;
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InvoiceDesign extends Model
{
    protected $fillable = [
        'brand_name',
        'tagline',
        'phone',
        'email',
        'address',
        'accent_color',
        'header_color',
        'thank_you',
        'footer_note',
        'logo_path',
    ];

    protected function casts(): array
    {
        return [
            'tagline' => 'array',
            'address' => 'array',
            'thank_you' => 'array',
            'footer_note' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (InvoiceDesign $design): void {
            if (! $design->isDirty('logo_path')) {
                return;
            }

            $old = $design->getOriginal('logo_path');
            if (is_string($old) && $old !== '' && $old !== $design->logo_path) {
                Storage::disk('public')->delete($old);
            }
        });
    }

    public static function current(): self
    {
        $existing = static::query()->first();

        return $existing ?? static::query()->create(static::defaults());
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'brand_name' => (string) config('aldawy.invoice_sender_name', 'AL-DAWY'),
            'tagline' => [
                'en' => 'Fresh fruits & vegetables, delivered',
                'ar' => 'فواكه وخضروات طازجة حتى باب البيت',
            ],
            'accent_color' => '#16a34a',
            'header_color' => '#14532d',
            'thank_you' => [
                'en' => 'Thank you for your order. We will prepare it with care.',
                'ar' => 'شكراً لطلبك. سنجهّزه بعناية.',
            ],
            'footer_note' => [
                'en' => 'Please keep this invoice for your records.',
                'ar' => 'يرجى الاحتفاظ بهذه الفاتورة.',
            ],
        ];
    }

    public static function hex(?string $color, string $fallback): string
    {
        $color = is_string($color) ? trim($color) : '';

        return preg_match('/^#[0-9A-Fa-f]{6}$/', $color) === 1 ? $color : $fallback;
    }

    public function accent(): string
    {
        return self::hex($this->accent_color, '#16a34a');
    }

    public function header(): string
    {
        return self::hex($this->header_color, '#14532d');
    }

    public function localized(string $attribute): string
    {
        $value = $this->getAttribute($attribute);
        if (! is_array($value)) {
            return '';
        }

        $locale = app()->getLocale();
        $text = trim((string) ($value[$locale] ?? ''));
        if ($text !== '') {
            return $text;
        }

        foreach (['en', 'ar'] as $fallback) {
            $text = trim((string) ($value[$fallback] ?? ''));
            if ($text !== '') {
                return $text;
            }
        }

        return '';
    }

    public function logoFile(): ?string
    {
        $path = is_string($this->logo_path) ? str_replace('\\', '/', $this->logo_path) : '';
        if ($path === '' || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return str_replace('\\', '/', Storage::disk('public')->path($path));
    }

    public function forgetCachedInvoices(): void
    {
        Order::query()->whereNotNull('invoice_path')->update(['invoice_path' => null]);

        $disk = Storage::disk('local');
        foreach ($disk->allFiles('invoices') as $file) {
            $disk->delete($file);
        }
    }
}

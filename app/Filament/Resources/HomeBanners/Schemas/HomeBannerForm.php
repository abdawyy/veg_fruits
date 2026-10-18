<?php

namespace App\Filament\Resources\HomeBanners\Schemas;

use App\Models\Product;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class HomeBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Visibility & order'))
                    ->columns(2)
                    ->schema(self::visibilitySchema()),
                Section::make(__('Arabic / English copy'))
                    ->columns(2)
                    ->schema(self::copySchema()),
                Section::make(__('Links & media'))
                    ->columns(1)
                    ->schema(self::mediaSchema()),
                Section::make(__('Rainbow strip (CSS gradient)'))
                    ->columns(3)
                    ->schema(self::gradientSchema()),
            ]);
    }

    /**
     * Fields for the “create multiple” repeater (one banner per item).
     *
     * @return list<Component>
     */
    public static function repeaterItemSchema(): array
    {
        return [
            ...self::visibilitySchema(includeSortOrder: false),
            ...self::copySchema(),
            ...self::mediaSchema(),
            ...self::gradientSchema(),
        ];
    }

    /**
     * @return list<Component>
     */
    private static function visibilitySchema(bool $includeSortOrder = true): array
    {
        $fields = [
            Toggle::make('is_active')->default(true),
            DateTimePicker::make('starts_at')->seconds(false),
            DateTimePicker::make('ends_at')->seconds(false),
        ];

        if ($includeSortOrder) {
            array_unshift($fields, TextInput::make('sort_order')->numeric()->default(0)->required());
        }

        return $fields;
    }

    /**
     * @return list<Component>
     */
    private static function copySchema(): array
    {
        return [
            TextInput::make('title.en')->label(__('Title (English)'))->required()->maxLength(255),
            TextInput::make('title.ar')->label(__('Title (Arabic)'))->required()->maxLength(255),
            TextInput::make('subtitle.en')->label(__('Subtitle (English)'))->maxLength(500),
            TextInput::make('subtitle.ar')->label(__('Subtitle (Arabic)'))->maxLength(500),
            TextInput::make('badge_text.en')->label(__('Badge (English)'))->maxLength(120),
            TextInput::make('badge_text.ar')->label(__('Badge (Arabic)'))->maxLength(120),
            TextInput::make('cta_label.en')->label(__('Button (English)'))->maxLength(120),
            TextInput::make('cta_label.ar')->label(__('Button (Arabic)'))->maxLength(120),
        ];
    }

    /**
     * @return list<Component>
     */
    private static function mediaSchema(): array
    {
        return [
            Select::make('cta_product_id')
                ->label(__('Button product'))
                ->placeholder(__('Search banana, apple…'))
                ->searchable()
                ->live()
                ->dehydrated(false)
                ->options(fn (): array => self::productChoices())
                ->getSearchResultsUsing(fn (string $search): array => self::productChoices($search))
                ->getOptionLabelUsing(function ($value): ?string {
                    $product = Product::query()->find($value);

                    return $product ? self::productLabel($product) : null;
                })
                ->afterStateHydrated(function (Select $component, Get $get): void {
                    $url = (string) $get('cta_url');
                    if (preg_match('#/products/(\d+)(?:$|[/?])#', $url, $match) === 1) {
                        $component->state((int) $match[1]);
                    }
                })
                ->afterStateUpdated(function ($state, Set $set): void {
                    if (blank($state)) {
                        return;
                    }

                    $product = Product::query()->find($state);
                    if ($product === null) {
                        return;
                    }

                    $set('cta_url', route('store.product', $product, absolute: false));
                })
                ->helperText(__('Search a product by name. The button below opens that product.')),
            TextInput::make('cta_url')
                ->label(__('Button link'))
                ->maxLength(2048)
                ->placeholder('/products/12')
                ->helperText(__('Filled when you pick a product. You can still paste another link.')),
            FileUpload::make('image_path')
                ->label(__('Banner image (upload)'))
                ->image()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                ->disk('public')
                ->directory('home-banners')
                ->visibility('public')
                ->maxSize(10240)
                ->nullable()
                ->fetchFileInformation(false)
                ->saveUploadedFileUsing(function (TemporaryUploadedFile $file): ?string {
                    try {
                        if (! $file->exists()) {
                            return null;
                        }

                        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                        $extension = preg_replace('/[^a-z0-9]+/', '', $extension) ?: 'jpg';
                        $name = Str::ulid().'.'.$extension;
                        $stored = $file->storeAs('home-banners', $name, 'public');

                        if (! is_string($stored) || $stored === '') {
                            return null;
                        }

                        return Storage::disk('public')->exists($stored) ? $stored : null;
                    } catch (Throwable) {
                        return null;
                    }
                })
                ->helperText(__('Uploaded image is shown before the external URL below.')),
            TextInput::make('image_url')->url()->maxLength(2048)->label(__('Banner image URL (optional)')),
            Select::make('hot_product_skus')
                ->label(__('Hot products'))
                ->placeholder(__('Search banana, apple…'))
                ->multiple()
                ->searchable()
                ->options(fn (): array => self::productSkuChoices())
                ->getSearchResultsUsing(fn (string $search): array => self::productSkuChoices($search))
                ->getOptionLabelsUsing(function (mixed $values): array {
                    if (is_string($values)) {
                        $values = array_values(array_filter(array_map('trim', explode(',', $values))));
                    }

                    if (! is_array($values)) {
                        return [];
                    }

                    return self::productSkuChoices(selected: array_map('strval', $values));
                })
                ->formatStateUsing(function ($state): array {
                    if (is_array($state)) {
                        return array_values(array_filter($state, fn ($sku) => filled($sku)));
                    }

                    if (! is_string($state) || trim($state) === '') {
                        return [];
                    }

                    return array_values(array_filter(array_map('trim', explode(',', $state))));
                })
                ->dehydrateStateUsing(function ($state): ?string {
                    $skus = is_array($state) ? $state : (filled($state) ? explode(',', (string) $state) : []);
                    $skus = array_values(array_filter(array_map(fn ($sku) => trim((string) $sku), $skus)));

                    return $skus === [] ? null : implode(',', $skus);
                })
                ->helperText(__('These products appear on the banner. Search by name.')),
        ];
    }

    /**
     * @return list<Component>
     */
    private static function gradientSchema(): array
    {
        return [
            ColorPicker::make('gradient_from')->default('#f97316'),
            ColorPicker::make('gradient_mid')->default('#eab308'),
            ColorPicker::make('gradient_to')->default('#22c55e'),
        ];
    }

    /**
     * @return array<int|string, string>
     */
    private static function productChoices(?string $search = null): array
    {
        return self::matchingProducts($search)
            ->mapWithKeys(fn (Product $product): array => [
                $product->getKey() => self::productLabel($product),
            ])
            ->all();
    }

    /**
     * @param  list<string>  $selected
     * @return array<string, string>
     */
    private static function productSkuChoices(?string $search = null, array $selected = []): array
    {
        $products = self::matchingProducts($search);

        if ($selected !== []) {
            $products = Product::query()
                ->active()
                ->whereIn('sku', $selected)
                ->get()
                ->merge($products)
                ->unique('sku')
                ->values();
        }

        return $products
            ->mapWithKeys(fn (Product $product): array => [
                $product->sku => self::productLabel($product),
            ])
            ->all();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Product>
     */
    private static function matchingProducts(?string $search)
    {
        $query = Product::query()->active()->orderBy('sku');

        if (filled($search)) {
            $query->search($search);
        }

        return $query->limit(20)->get();
    }

    private static function productLabel(Product $product): string
    {
        $english = (string) $product->getTranslation('name', 'en');
        $arabic = (string) $product->getTranslation('name', 'ar');
        $name = $english !== '' && $arabic !== '' && $english !== $arabic
            ? $english.' / '.$arabic
            : ($english !== '' ? $english : $arabic);

        return trim($name.' ('.$product->sku.')');
    }
}

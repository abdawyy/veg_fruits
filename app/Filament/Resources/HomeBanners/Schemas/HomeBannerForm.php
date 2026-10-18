<?php

namespace App\Filament\Resources\HomeBanners\Schemas;

use App\Models\HomeBanner;
use App\Models\Product;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

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
        ];
    }

    /**
     * @return list<Component>
     */
    private static function mediaSchema(): array
    {
        return [
            Repeater::make('cta_buttons')
                ->label(__('Buttons'))
                ->helperText(__('Add several buttons. Each can open a different product from any category, or any custom link.'))
                ->schema([
                    TextInput::make('label.en')->label(__('Button (English)'))->maxLength(120),
                    TextInput::make('label.ar')->label(__('Button (Arabic)'))->maxLength(120),
                    Select::make('product_id')
                        ->label(__('Product'))
                        ->placeholder(__('Search banana, apple…'))
                        ->searchable()
                        ->live()
                        ->options(fn (): array => self::productChoices())
                        ->getSearchResultsUsing(fn (string $search): array => self::productChoices($search))
                        ->getOptionLabelUsing(function ($value): ?string {
                            $product = Product::query()->with('category')->find($value);

                            return $product ? self::productLabel($product) : null;
                        })
                        ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                            if (blank($state)) {
                                return;
                            }

                            $product = Product::query()->find($state);
                            if ($product === null) {
                                return;
                            }

                            $set('url', route('store.product', $product, absolute: false));

                            if (blank($get('label.en'))) {
                                $set('label.en', (string) $product->getTranslation('name', 'en'));
                            }
                            if (blank($get('label.ar'))) {
                                $set('label.ar', (string) $product->getTranslation('name', 'ar'));
                            }
                        }),
                    TextInput::make('url')
                        ->label(__('Link'))
                        ->maxLength(2048)
                        ->placeholder('/products/12')
                        ->helperText(__('Filled when you pick a product. You can still paste another link (shop, category, external…).')),
                ])
                ->columns(2)
                ->defaultItems(1)
                ->minItems(0)
                ->maxItems(6)
                ->reorderable()
                ->collapsible()
                ->itemLabel(function (array $state): ?string {
                    $en = trim((string) data_get($state, 'label.en', ''));
                    $ar = trim((string) data_get($state, 'label.ar', ''));
                    $label = $en !== '' ? $en : $ar;

                    return $label !== '' ? $label : __('New button');
                })
                ->afterStateHydrated(function (Repeater $component, mixed $state): void {
                    if (filled($state)) {
                        return;
                    }

                    $record = $component->getRecord();
                    if ($record instanceof HomeBanner) {
                        $component->state($record->resolvedCtaButtons());
                    }
                })
                ->dehydrateStateUsing(fn ($state): array => HomeBanner::normalizeCtaButtons($state))
                ->columnSpanFull(),
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
     * @return Collection<int, Product>
     */
    private static function matchingProducts(?string $search)
    {
        $query = Product::query()->active()->with('category')->orderBy('sku');

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

        $category = $product->category;
        $categoryName = $category
            ? (string) ($category->getTranslation('name', 'en') ?: $category->getTranslation('name', 'ar') ?: '')
            : '';

        $label = trim($name.' ('.$product->sku.')');

        return $categoryName !== '' ? $label.' · '.$categoryName : $label;
    }
}

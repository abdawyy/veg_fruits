<?php

namespace App\Filament\Pages;

use App\Models\ShopSetting;
use App\Support\MinimumOrder;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class MinimumOrderPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Shipping';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'minimum-order';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public ?ShopSetting $record = null;

    public static function getNavigationLabel(): string
    {
        return __('Minimum order');
    }

    public function getTitle(): string
    {
        return __('Minimum order');
    }

    public function getSubheading(): ?string
    {
        return __('The smallest product total a customer can check out with.');
    }

    public function mount(): void
    {
        $this->record = ShopSetting::current();
        $this->form->fill($this->record->attributesToArray());
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->operation('edit')
            ->model($this->record)
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Product subtotal'))
                ->description(__('Delivery, packaging, and coupons do not count. Use 0 to allow any non-empty cart.'))
                ->schema([
                    TextInput::make('min_order_subtotal')
                        ->label(__('Minimum order'))
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->step(0.01)
                        ->suffix((string) config('aldawy.currency', 'EGP')),
                ]),
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label(__('Save minimum order'))
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record ??= ShopSetting::current();
        $this->record->min_order_subtotal = MinimumOrder::normalize($data['min_order_subtotal'] ?? '0');
        $this->record->save();

        Notification::make()
            ->title(__('Minimum order saved'))
            ->success()
            ->send();
    }
}

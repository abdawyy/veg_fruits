<?php

namespace App\Filament\Pages;

use App\Actions\Invoices\GenerateInvoicePdfAction;
use App\Models\InvoiceDesign;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
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
class InvoiceDesignPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Storefront';

    protected static ?int $navigationSort = 8;

    protected static ?string $slug = 'invoice-design';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public ?InvoiceDesign $record = null;

    public static function getNavigationLabel(): string
    {
        return __('Invoice design');
    }

    public function getTitle(): string
    {
        return __('Invoice design');
    }

    public function getSubheading(): ?string
    {
        return __('Colors, logo, and wording customers see on the order PDF.');
    }

    public function mount(): void
    {
        $this->record = InvoiceDesign::current();
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
            Section::make(__('Look'))
                ->description(__('These colors and the logo appear at the top of every customer invoice.'))
                ->columns(2)
                ->schema([
                    TextInput::make('brand_name')
                        ->label(__('Store name on the invoice'))
                        ->required()
                        ->maxLength(120),
                    FileUpload::make('logo_path')
                        ->label(__('Logo'))
                        ->image()
                        ->disk('public')
                        ->directory('invoice-branding')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->nullable(),
                    TextInput::make('tagline.en')->label(__('Tagline (English)'))->maxLength(180),
                    TextInput::make('tagline.ar')->label(__('Tagline (Arabic)'))->maxLength(180),
                    ColorPicker::make('header_color')->label(__('Header color'))->default('#14532d'),
                    ColorPicker::make('accent_color')->label(__('Accent color'))->default('#16a34a'),
                ]),
            Section::make(__('Store details'))
                ->description(__('Printed under “From” so the customer knows who sent the order.'))
                ->columns(2)
                ->schema([
                    TextInput::make('phone')->label(__('Phone'))->maxLength(64),
                    TextInput::make('email')->label(__('Email'))->email()->maxLength(255),
                    Textarea::make('address.en')->label(__('Address (English)'))->rows(2)->columnSpanFull(),
                    Textarea::make('address.ar')->label(__('Address (Arabic)'))->rows(2)->columnSpanFull(),
                ]),
            Section::make(__('Message'))
                ->columns(2)
                ->schema([
                    Textarea::make('thank_you.en')->label(__('Thank-you note (English)'))->rows(2),
                    Textarea::make('thank_you.ar')->label(__('Thank-you note (Arabic)'))->rows(2),
                    Textarea::make('footer_note.en')->label(__('Footer (English)'))->rows(2),
                    Textarea::make('footer_note.ar')->label(__('Footer (Arabic)'))->rows(2),
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
                            ->label(__('Save invoice design'))
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    /**
     * @return list<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label(__('Preview latest invoice'))
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->action(function () {
                    $order = Order::query()->latest('id')->first();
                    if ($order === null) {
                        Notification::make()
                            ->title(__('Place an order first, then preview its invoice.'))
                            ->warning()
                            ->send();

                        return null;
                    }

                    return app(GenerateInvoicePdfAction::class)->download($order, refresh: true);
                }),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['accent_color'] = InvoiceDesign::hex($data['accent_color'] ?? null, '#16a34a');
        $data['header_color'] = InvoiceDesign::hex($data['header_color'] ?? null, '#14532d');
        $data['brand_name'] = trim((string) ($data['brand_name'] ?? '')) ?: 'AL-DAWY';

        $this->record ??= InvoiceDesign::current();
        $this->record->fill($data);
        $this->record->save();
        $this->record->forgetCachedInvoices();

        Notification::make()
            ->title(__('Invoice design saved'))
            ->body(__('The next download uses this design.'))
            ->success()
            ->send();
    }
}

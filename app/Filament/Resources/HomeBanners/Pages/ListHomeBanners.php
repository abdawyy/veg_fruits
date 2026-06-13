<?php

namespace App\Filament\Resources\HomeBanners\Pages;

use App\Exports\HomeBannersExport;
use App\Filament\Resources\HomeBanners\HomeBannerResource;
use App\Filament\Resources\HomeBanners\Schemas\HomeBannerForm;
use App\Imports\HomeBannersImport;
use App\Models\HomeBanner;
use App\Support\HomeBannerAttributes;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListHomeBanners extends ListRecords
{
    protected static string $resource = HomeBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label(__('Export Excel'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(new HomeBannersExport, 'home-banners-'.now()->format('Y-m-d').'.xlsx')),
            Action::make('create_multiple')
                ->label(__('Create multiple'))
                ->icon('heroicon-o-squares-plus')
                ->color('success')
                ->modalHeading(__('Create multiple banners'))
                ->modalDescription(__('Add several carousel slides in one step. Sort order is assigned automatically (0, 1, 2…).'))
                ->modalWidth('7xl')
                ->schema([
                    Repeater::make('banners')
                        ->label(__('Banners'))
                        ->schema(HomeBannerForm::repeaterItemSchema())
                        ->minItems(1)
                        ->defaultItems(2)
                        ->collapsible()
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string => filled(data_get($state, 'title.en'))
                            ? (string) data_get($state, 'title.en')
                            : __('New banner'))
                        ->columns(2)
                        ->columnSpanFull(),
                ])
                ->action(function (array $data): void {
                    $rows = $data['banners'] ?? [];
                    if ($rows === []) {
                        Notification::make()->title(__('Add at least one banner'))->danger()->send();

                        return;
                    }

                    $baseSort = (int) (HomeBanner::query()->max('sort_order') ?? -1);

                    foreach ($rows as $index => $row) {
                        if (! is_array($row)) {
                            continue;
                        }

                        HomeBanner::query()->create(
                            HomeBannerAttributes::fromFormRow($row, $baseSort + $index + 1),
                        );
                    }

                    Notification::make()
                        ->title(__('Banners created'))
                        ->body(__(':count banner(s) added to the home carousel.', ['count' => count($rows)]))
                        ->success()
                        ->send();
                }),
            Action::make('import_excel')
                ->label(__('Import Excel'))
                ->icon('heroicon-o-arrow-up-tray')
                ->modalDescription(__('Rows with an id update that banner. Leave id empty to create new banners. Upload images per record after import.'))
                ->schema([
                    FileUpload::make('file')
                        ->label(__('Spreadsheet'))
                        ->disk('local')
                        ->directory('imports')
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $relative = $data['file'] ?? null;
                    if (! is_string($relative) || $relative === '') {
                        Notification::make()->title(__('Missing file'))->danger()->send();

                        return;
                    }
                    $path = Storage::disk('local')->path($relative);
                    Excel::import(new HomeBannersImport, $path);
                    Notification::make()->title(__('Import finished'))->success()->send();
                }),
            CreateAction::make(),
        ];
    }
}

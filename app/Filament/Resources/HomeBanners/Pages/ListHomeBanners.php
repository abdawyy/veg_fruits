<?php

namespace App\Filament\Resources\HomeBanners\Pages;

use App\Exports\HomeBannersExport;
use App\Filament\Resources\HomeBanners\HomeBannerResource;
use App\Imports\HomeBannersImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
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
            Action::make('import_excel')
                ->label(__('Import Excel'))
                ->icon('heroicon-o-arrow-up-tray')
                ->modalDescription(__('Updates existing banners by id. Upload banner images separately in each record.'))
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

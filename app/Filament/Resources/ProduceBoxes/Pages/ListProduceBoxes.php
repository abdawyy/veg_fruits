<?php

namespace App\Filament\Resources\ProduceBoxes\Pages;

use App\Exports\ProduceBoxesExport;
use App\Filament\Resources\ProduceBoxes\ProduceBoxResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListProduceBoxes extends ListRecords
{
    protected static string $resource = ProduceBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label(__('Export Excel'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(new ProduceBoxesExport, 'produce-boxes-'.now()->format('Y-m-d').'.xlsx')),
            CreateAction::make(),
        ];
    }
}

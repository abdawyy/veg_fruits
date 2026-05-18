<?php

namespace App\Filament\Resources\SiteVisitors\Pages;

use App\Exports\SiteVisitorsExport;
use App\Exports\SitePageViewsExport;
use App\Filament\Resources\SiteVisitors\SiteVisitorResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListSiteVisitors extends ListRecords
{
    protected static string $resource = SiteVisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_page_views_excel')
                ->label(__('Export page views (Excel)'))
                ->icon('heroicon-o-table-cells')
                ->color('gray')
                ->action(fn () => Excel::download(new SitePageViewsExport, 'site-page-views-'.now()->format('Y-m-d').'.xlsx')),
            Action::make('export_excel')
                ->label(__('Export Excel'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(new SiteVisitorsExport, 'site-visitors-'.now()->format('Y-m-d').'.xlsx')),
        ];
    }
}

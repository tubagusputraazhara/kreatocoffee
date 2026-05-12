<?php

namespace App\Filament\Resources\JurnalUmumResource\Pages;

use App\Filament\Exports\JurnalUmumExporter;
use App\Filament\Resources\JurnalUmumResource;
use Filament\Actions\ExportAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListJurnalUmums extends ListRecords
{
    protected static string $resource = JurnalUmumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Export Excel
            ExportAction::make()
                ->label('Export Excel')
                ->exporter(JurnalUmumExporter::class)
                ->color('success'),

            // Export PDF
            Action::make('export_pdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(route('jurnal.export.pdf'))
                ->openUrlInNewTab(),
        ];
    }
}
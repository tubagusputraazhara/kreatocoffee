<?php

namespace App\Filament\Exports;

use App\Models\menu;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MenuExporter extends Exporter
{
    protected static ?string $model = menu::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('nama_menu')->label('Nama Menu'),
            ExportColumn::make('kategori')->label('Kategori'),
            ExportColumn::make('harga')->label('Harga'),
            ExportColumn::make('stok')->label('Stok'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data menu selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
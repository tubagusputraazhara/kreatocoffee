<?php

namespace App\Filament\Exports;

use App\Models\bahanBaku;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BahanBakuExporter extends Exporter
{
    protected static ?string $model = bahanBaku::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID Bahan'),

            ExportColumn::make('nama_bahan')
                ->label('Nama Bahan'),

            ExportColumn::make('kategori')
                ->label('Kategori'),

            ExportColumn::make('satuan')
                ->label('Satuan'),

            ExportColumn::make('stok')
                ->label('Stok'),

            ExportColumn::make('harga')
                ->label('Harga'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export bahan baku selesai. ' .
            number_format($export->successful_rows) .
            ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' .
                number_format($failedRowsCount) .
                ' baris gagal diekspor.';
        }

        return $body;
    }
}
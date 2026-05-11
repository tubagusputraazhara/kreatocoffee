<?php

namespace App\Filament\Exports;

use App\Models\pembelianBahanBaku;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PembelianBahanBakuExporter extends Exporter
{
    protected static ?string $model = pembelianBahanBaku::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('no_faktur')
                ->label('No Faktur'),

            ExportColumn::make('tgl')
                ->label('Tanggal Pembelian'),

            ExportColumn::make('bahanBaku.nama_bahan')
                ->label('Nama Bahan'),

            ExportColumn::make('jumlah')
                ->label('Jumlah'),

            ExportColumn::make('harga_satuan')
                ->label('Harga Satuan'),

            ExportColumn::make('total_harga')
                ->label('Total Harga'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export pembelian bahan baku selesai. ' .
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
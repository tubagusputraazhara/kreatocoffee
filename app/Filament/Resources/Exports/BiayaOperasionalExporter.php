<?php

namespace App\Filament\Exports;

use App\Models\BiayaOperasional;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BiayaOperasionalExporter extends Exporter
{
    protected static ?string $model = BiayaOperasional::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_transaksi')->label('ID Transaksi'),
            ExportColumn::make('tanggal_transaksi')->label('Tanggal Transaksi'),
            ExportColumn::make('kategori')->label('Kategori'),
            ExportColumn::make('keterangan')->label('Keterangan'),
            ExportColumn::make('jumlah')->label('Jumlah')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ExportColumn::make('pelanggan.nama')->label('Nama Pelanggan'),
            ExportColumn::make('karyawan.nama')->label('Nama Karyawan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export biaya operasional selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
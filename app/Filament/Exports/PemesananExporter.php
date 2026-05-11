<?php

namespace App\Filament\Exports;

use App\Models\Pemesanan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PemesananExporter extends Exporter
{
    protected static ?string $model = Pemesanan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_pemesanan')->label('ID Pemesanan'),
            ExportColumn::make('id_pelanggan')->label('ID Pelanggan'),
            ExportColumn::make('nama_pelanggan')->label('Nama Pelanggan'),
            ExportColumn::make('id_meja')->label('No Meja'),
            ExportColumn::make('nama_pesanan')->label('Menu dipesan'),
            ExportColumn::make('harga_satuan')->label('Harga Satuan'),
            ExportColumn::make('jumlah')->label('Qty'),
            ExportColumn::make('total_harga')->label('Total Harga'),
            ExportColumn::make('catatan')->label('Catatan'),
            ExportColumn::make('created_at')->label('Waktu Pesan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data transaksi pemesanan selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
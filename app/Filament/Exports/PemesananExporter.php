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
            ExportColumn::make('kode_pemesanan')->label('Kode Pemesanan'),
            ExportColumn::make('nama_pemesan')->label('Nama Pemesan'),
            ExportColumn::make('no_meja')->label('No Meja'),
            ExportColumn::make('no_wa')->label('No WhatsApp'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('sumber')->label('Sumber'),
            ExportColumn::make('total_harga')
                ->label('Total Harga')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ExportColumn::make('status')->label('Status'),
            ExportColumn::make('catatan')->label('Catatan'),
            ExportColumn::make('created_at')->label('Waktu Pesan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data pemesanan selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
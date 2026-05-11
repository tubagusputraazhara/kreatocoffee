<?php

namespace App\Filament\Exports;

use App\Models\Pelanggan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PelangganExporter extends Exporter
{
    protected static ?string $model = Pelanggan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_pelanggan')->label('ID Pelanggan'),
            ExportColumn::make('nama')->label('Nama'),
            ExportColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
            ExportColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
            ExportColumn::make('no_telepon')->label('No. Telepon'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('alamat')->label('Alamat'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export pelanggan selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
//
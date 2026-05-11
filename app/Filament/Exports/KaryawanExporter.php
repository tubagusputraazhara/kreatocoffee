<?php

namespace App\Filament\Exports;

use App\Models\Karyawan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class KaryawanExporter extends Exporter
{
    protected static ?string $model = Karyawan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_karyawan')->label('ID Karyawan'),
            ExportColumn::make('nama')->label('Nama'),
            ExportColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
            ExportColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
            ExportColumn::make('gaji')->label('Gaji'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export karyawan selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
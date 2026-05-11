<?php

namespace App\Filament\Exports;

use App\Models\Penggajian;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PenggajianExporter extends Exporter
{
    protected static ?string $model = Penggajian::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_penggajian')->label('ID Penggajian'),
            ExportColumn::make('karyawan.nama')->label('Nama Karyawan'),
            ExportColumn::make('bulan')->label('Bulan')
                ->formatStateUsing(fn (int $state): string => Penggajian::namaBulan()[$state] ?? $state),
            ExportColumn::make('tahun')->label('Tahun'),
            ExportColumn::make('gaji_pokok')->label('Gaji Pokok'),
            ExportColumn::make('tunjangan')->label('Tunjangan'),
            ExportColumn::make('potongan')->label('Potongan'),
            ExportColumn::make('gaji_bersih')->label('Gaji Bersih'),
            ExportColumn::make('status')->label('Status'),
            ExportColumn::make('tanggal_bayar')->label('Tanggal Bayar'),
            ExportColumn::make('keterangan')->label('Keterangan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export penggajian selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
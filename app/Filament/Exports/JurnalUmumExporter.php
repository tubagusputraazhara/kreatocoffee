<?php

namespace App\Filament\Exports;

use App\Models\JurnalUmum;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class JurnalUmumExporter extends Exporter
{
    protected static ?string $model = JurnalUmum::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nomor_jurnal')
                ->label('Nomor Jurnal'),

            ExportColumn::make('tanggal_jurnal')
                ->label('Tanggal'),

            ExportColumn::make('keterangan')
                ->label('Keterangan'),

            ExportColumn::make('ref')
                ->label('Ref'),

            ExportColumn::make('total_debit')
                ->label('Total Debit')
                ->state(fn ($record) => $record->detailJurnal->sum('debit'))
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

            ExportColumn::make('total_kredit')
                ->label('Total Kredit')
                ->state(fn ($record) => $record->detailJurnal->sum('kredit'))
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export jurnal umum selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
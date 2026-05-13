<?php

namespace App\Filament\Resources\PemesananResource\Pages;

use App\Filament\Resources\PemesananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use App\Filament\Exports\PemesananExporter;
use App\Models\Pemesanan;
use Barryvdh\DomPDF\Facade\Pdf;

class ListPemesanans extends ListRecords
{
    protected static string $resource = PemesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Export Pemesanan')
                ->icon('heroicon-o-arrow-up-tray')
                ->exporter(PemesananExporter::class)
                ->color('success'),

            Action::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $pemesanans = Pemesanan::with('details') // ← tambah with details
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $pdf = Pdf::loadView('pdf.pemesanan', ['pemesanans' => $pemesanans])
                        ->setPaper('a4', 'landscape'); // ← landscape biar lega

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'Laporan-Pemesanan-' . now()->format('Y-m-d') . '.pdf'
                    );
                }),
        ];
    }
}
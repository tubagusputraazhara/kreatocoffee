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
            // Export dan PDF di sini agar posisinya paling atas (Header Halaman)
            ExportAction::make()
                ->label('Export Pemesanan')
                ->exporter(PemesananExporter::class)
                ->color('success'),

            Action::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $pemesanans = Pemesanan::all();
                    $pdf = Pdf::loadView('pdf.pemesanan', ['pemesanans' => $pemesanans]);
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'daftar-pemesanan.pdf'
                    );
                }),
        ];
    }
}
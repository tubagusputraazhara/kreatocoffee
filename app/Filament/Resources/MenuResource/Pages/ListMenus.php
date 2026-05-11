<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

// Tambahan
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use App\Filament\Exports\MenuExporter;
use App\Models\Menu;
use Barryvdh\DomPDF\Facade\Pdf;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 1. Tombol export excel (Posisi Atas)
            ExportAction::make()
                ->label('Export Menu')
                ->exporter(MenuExporter::class)
                ->color('success'),

            // 2. Tombol unduh PDF (Posisi Atas)
            Action::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $menus = Menu::all();
                    $pdf = Pdf::loadView('pdf.menu', ['menus' => $menus]);
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'daftar-menu.pdf'
                    );
                })
        ];
    }
}
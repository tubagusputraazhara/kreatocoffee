<?php

namespace App\Filament\Resources\BiayaOperasionalResource\Pages;

use App\Filament\Resources\BiayaOperasionalResource;
use App\Filament\Resources\BiayaOperasionalResource\Widgets\BiayaOperasionalStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayaOperasionals extends ListRecords
{
    protected static string $resource = BiayaOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Biaya Operasional'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BiayaOperasionalStats::class,
        ];
    }
}
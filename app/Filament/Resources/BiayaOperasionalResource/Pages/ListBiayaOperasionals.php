<?php

namespace App\Filament\Resources\BiayaOperasionalResource\Pages;

use App\Filament\Resources\BiayaOperasionalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayaOperasionals extends ListRecords
{
    protected static string $resource = BiayaOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

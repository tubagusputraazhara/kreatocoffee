<?php

namespace App\Filament\Resources\BiayaOperasionalResource\Pages;

use App\Filament\Resources\BiayaOperasionalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiayaOperasional extends EditRecord
{
    protected static string $resource = BiayaOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

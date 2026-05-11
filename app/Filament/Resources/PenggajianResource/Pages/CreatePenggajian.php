<?php

namespace App\Filament\Resources\PenggajianResource\Pages;

use App\Filament\Resources\PenggajianResource;
use App\Services\JurnalService;
use Filament\Resources\Pages\CreateRecord;

class CreatePenggajian extends CreateRecord
{
    protected static string $resource = PenggajianResource::class;

    protected function afterCreate(): void
    {
        JurnalService::jurnalPenggajian($this->record);
    }
}
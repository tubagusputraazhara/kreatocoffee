<?php

namespace App\Filament\Resources\BiayaOperasionalResource\Pages;

use App\Filament\Resources\BiayaOperasionalResource;
use App\Services\JurnalService; // ← tambahkan ini
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBiayaOperasional extends CreateRecord
{
    protected static string $resource = BiayaOperasionalResource::class;

    protected function afterCreate(): void
    {
    JurnalService::jurnalOperasional($this->record);
    }
}

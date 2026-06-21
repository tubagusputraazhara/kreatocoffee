<?php

namespace App\Filament\Resources\BukuBesarResource\Pages;

use App\Filament\Resources\BukuBesarResource;
use App\Filament\Resources\BukuBesarResource\Widgets\BukuBesar;
use Filament\Resources\Pages\ListRecords;

class ListBukuBesars extends ListRecords
{
    protected static string $resource = BukuBesarResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            BukuBesar::class,
        ];
    }
}
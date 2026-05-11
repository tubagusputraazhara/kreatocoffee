<?php

namespace App\Filament\Resources\JurnalUmumResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;

class DetailJurnalRelationManager extends RelationManager
{
    protected static string $relationship = 'detailJurnal';

    protected static ?string $title = 'Detail Jurnal';

    public function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('coa.nama_akun')
                    ->label('Akun'),

                TextColumn::make('debit')
                    ->money('IDR')
                    ->label('Debit'),

                TextColumn::make('kredit')
                    ->money('IDR')
                    ->label('Kredit'),

            ])
            ->actions([])
            ->bulkActions([]);
    }
}
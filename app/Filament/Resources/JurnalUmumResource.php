<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalUmumResource\Pages;
use App\Models\JurnalUmum;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;

class JurnalUmumResource extends Resource
{
    protected static ?string $model = JurnalUmum::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Jurnal Umum';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $pluralLabel = 'Jurnal Umum';

    protected static ?string $modelLabel = 'Jurnal Umum';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nomor_jurnal')
                    ->label('Nomor Jurnal')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_jurnal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('detailJurnal_count')
                    ->counts('detailJurnal')
                    ->label('Detail'),

            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
            ])

            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\JurnalUmumResource\RelationManagers\DetailJurnalRelationManager::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurnalUmums::route('/'),
        ];
    }
}
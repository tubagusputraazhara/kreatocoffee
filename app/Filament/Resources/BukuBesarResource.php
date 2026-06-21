<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuBesarResource\Pages;
use App\Filament\Resources\BukuBesarResource\Widgets\BukuBesar;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class BukuBesarResource extends Resource
{
    protected static ?string $model = null;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Buku Besar';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $pluralModelLabel = 'Buku Besar';
    protected static ?string $modelLabel = 'Buku Besar';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->filters([])
            ->actions([])
            ->bulkActions([])
            ->paginated(false);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [
            BukuBesar::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBukuBesars::route('/'),
        ];
    }
}
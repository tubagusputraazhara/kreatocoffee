<?php

namespace App\Filament\Resources;

use App\Filament\Resources\bahanBakuResource\Pages;
use App\Models\bahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class bahanBakuResource extends Resource
{
    protected static ?string $model = bahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Bahan Baku';
    protected static ?string $pluralLabel = 'Bahan Baku';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Forms\Components\TextInput::make('kode_bahan')
                  //  ->required()
                  //  ->unique(ignoreRecord: true)
                  //  ->maxLength(50),

                Forms\Components\TextInput::make('nama_bahan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('kategori')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('satuan')
                    ->required()
                    ->placeholder('kg, liter, pcs'),

                Forms\Components\TextInput::make('stok')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                //Forms\Components\Textarea::make('deskripsi')
                  //  ->rows(3)
                  //  ->nullable(),

                //Forms\Components\FileUpload::make('gambar')
                  //  ->image()
                  //  ->directory('bahan-baku')
                  //  ->nullable(),
            ]);
    }
//
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Bahan')
                    ->sortable()
                    ->searchable(),
                // -------------------------------

               // Tables\Columns\TextColumn::make('kode_bahan')
                 //   ->searchable()
                 //   ->sortable(),

                Tables\Columns\TextColumn::make('nama_bahan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('stok')
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan'),

                Tables\Columns\TextColumn::make('harga')
                    ->money('IDR', true),

                //Tables\Columns\TextColumn::make('supplier')
                   // ->toggleable(),

                //Tables\Columns\ImageColumn::make('gambar')
                  //  ->size(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListbahanBakus::route('/'),
            'create' => Pages\CreatebahanBaku::route('/create'),
            'edit' => Pages\EditbahanBaku::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bahan_baku_id')
                    ->relationship('bahanBaku', 'nama_bahan')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('nama_supplier')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_telephone')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $set('total', (float) $get('jumlah') * (float) $get('harga_satuan'));
                    }),
                Forms\Components\Select::make('satuan')
                    ->options([
                        'pcs' => 'Pcs',
                        'kg' => 'Kg',
                        'gram' => 'Gram',
                        'liter' => 'Liter',
                        'lusin' => 'Lusin',
                        'box' => 'Box',
                        'karung' => 'Karung',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('harga_satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $set('total', (float) $get('jumlah') * (float) $get('harga_satuan'));
                    }),

                Forms\Components\TextInput::make('total')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->dehydrated(),
                                            ]);
                                    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bahan_baku_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telephone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}

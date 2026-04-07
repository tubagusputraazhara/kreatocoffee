<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetodePembayaranResource\Pages;
use App\Models\MetodePembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;

class MetodePembayaranResource extends Resource
{
    protected static ?string $model = MetodePembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Metode Pembayaran';
    protected static ?string $pluralLabel = 'Metode Pembayaran';
    protected static ?string $modelLabel = 'Metode Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_metode')
                    ->label('Nama Metode')
                    ->required()
                    ->maxLength(100),

                Select::make('jenis')
                    ->label('Jenis')
                    ->options([
                        'tunai' => 'Tunai',
                        'non_tunai' => 'Non Tunai',
                        'ewallet' => 'E-Wallet',
                    ])
                    ->required()
                    ->default('non_tunai'),

                TextInput::make('biaya_admin')
                    ->label('Biaya Admin')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_metode')
                    ->label('Nama Metode')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('jenis')
                    ->label('Jenis')
                    ->colors([
                        'success' => 'tunai',
                        'warning' => 'non_tunai',
                        'primary' => 'ewallet',
                    ]),

                TextColumn::make('biaya_admin')
                    ->label('Biaya Admin')
                    ->money('IDR')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada metode pembayaran')
            ->emptyStateDescription('Tambahkan metode pembayaran untuk memulai.');
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
            'index' => Pages\ListMetodePembayarans::route('/'),
            'create' => Pages\CreateMetodePembayaran::route('/create'),
            'edit' => Pages\EditMetodePembayaran::route('/{record}/edit'),
        ];
    }
}
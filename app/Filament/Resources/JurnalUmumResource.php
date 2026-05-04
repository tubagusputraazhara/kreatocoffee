<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalUmumResource\Pages;
use App\Models\JurnalUmum;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Form Components
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;

// Table Columns
use Filament\Tables\Columns\TextColumn;

class JurnalUmumResource extends Resource
{
    protected static ?string $model = JurnalUmum::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Jurnal Umum';

    protected static ?string $navigationGroup = 'Transaksi';    

    protected static ?string $pluralLabel = 'Jurnal Umum';

    protected static ?string $modelLabel = 'Jurnal Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor_jurnal')
                    ->label('Nomor Jurnal')
                    ->required()
                    ->unique(ignoreRecord: true),

                DatePicker::make('tanggal_jurnal')
                    ->label('Tanggal Jurnal')
                    ->required(),

                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_jurnal')
                    ->label('ID')
                    ->sortable(),

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
                    ->limit(50),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurnalUmums::route('/'),
            'create' => Pages\CreateJurnalUmum::route('/create'),
            'edit' => Pages\EditJurnalUmum::route('/{record}/edit'),
        ];
    }
}
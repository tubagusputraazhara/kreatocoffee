<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Models\pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PelangganResource extends Resource
{
    protected static ?string $model = pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $navigationGroup = 'Master Data';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Forms\Components\TextInput::make('kode_pelanggan')
                  //  ->required()
                    //->unique(ignoreRecord: true),
                    //Forms\Components\TextInput::make('kode_pelanggan')
                  //  ->required()
                    //->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('nama_pelanggan')
                    ->required(),

                //Forms\Components\TextInput::make('email')
                  //  ->email()
                    //->unique(ignoreRecord: true)
                    //->nullable(),

                Forms\Components\TextInput::make('no_hp')
                    ->required(),

                //Forms\Components\Textarea::make('alamat')
                    //->required(),

                //Forms\Components\Select::make('jenis_kelamin')
                  //  ->options([
                    //    'Laki-laki' => 'Laki-laki',
                      //  'Perempuan' => 'Perempuan',
                    //])
                    //->nullable(),

               // Forms\Components\DatePicker::make('tanggal_lahir')
                 //   ->nullable(),

               // Forms\Components\Toggle::make('is_active')
                 //   ->label('Status Aktif')
                   // ->default(true),

                //Forms\Components\Textarea::make('catatan')
                  //  ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->searchable(),
//
                Tables\Columns\TextColumn::make('no_hp'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Listpelanggans::route('/'),
            'create' => Pages\Createpelanggan::route('/create'),
            'edit' => Pages\Editpelanggan::route('/{record}/edit'),
        ];
    }
}
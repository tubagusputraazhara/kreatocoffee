<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Komponen Input Form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;

// Komponen Kolom Tabel
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; //produk

    protected static ?string $navigationGroup = 'Master Data'; //bagian ini
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Id Menu
                TextInput::make('id_menu')
                ->label('ID Menu')
                ->default(function () {
                $count = \App\Models\Menu::count(); //Menghitung jumlah data di tabel menu
                $nextNumber = $count + 1; //Menentukan nomor urut berikutnya
                return 'M' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT); //Menggabungkan huruf 'M' dengan angka yang diformat 3 digit (001)
        })
                ->readOnly() // Membuat kotak input tidak bisa diedit manual oleh user
                ->required() // Wajib untuk diisi
                ->unique(ignoreRecord: true), // Unique= tidak boleh sama, dan data sudah otomatis
             //   
                // Nama Menu
                TextInput::make('nama_menu')
                    ->label('Nama Menu')
                    ->required(),

                // Harga
                TextInput::make('harga')
                    ->label('Harga') //formatStateUsing(fn (string $state): string => 'Rp ' . number_format($state, 0, ',', '.')),
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                // Kategori (Makanan/Minuman)
                Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Makanan' => 'Makanan',
                        'Minuman' => 'Minuman',
                    ])
                    ->required(),

                // Gambar
                FileUpload::make('gambar')
                    ->label('Foto Produk')
                    ->image()
                    ->directory('menu-images')
                    ->required(),

                // Deskripsi
                Textarea::make('deskripsi')
                    ->label('Deskripsi Menu')
                    ->required(),

                // Status Menu
                //Toggle::make('is_admin')
                //    ->label('Tersedia')
                //    ->default(true)
                //    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan ID Menu
                //TextColumn::make('id')
                    //->label('ID Menu')
                    //->sortable(),

                TextColumn::make('id_menu')
                ->label('ID Menu')
                ->sortable(),

                // Menampilkan Nama Menu
                TextColumn::make('nama_menu')
                    ->label('Nama Menu')
                    ->searchable(),

                // Menampilkan Harga
                TextColumn::make('harga')
                    ->label('Harga') //prefix('Rp')
                    ->money('idr'), //, locale: 'id'

                // Menampilkan Kategori
                TextColumn::make('kategori')
                    ->label('Kategori'),

                // Menampilkan Gambar
                ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->size(50),

                // Menampilkan Deskripsi (Limit karakter agar rapi)
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50),

                // Menampilkan Status Tersedia (is_admin)
                //IconColumn::make('is_admin')
                   // ->label('Status Tersedia')
                    //->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemesananResource\Pages;
use App\Models\Pemesanan;
use App\Models\menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Komponen
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class PemesananResource extends Resource
{
    protected static ?string $model = Pemesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pemesanan';

    protected static ?string $navigationGroup = 'Transaksi'; //bagian ini

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Generate ID P-0001
                TextInput::make('id_pemesanan')
                    ->label('ID Pemesanan')
                    ->default(function () {
                        $count = \App\Models\Pemesanan::count();
                        return 'P-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
                    })
                    ->readOnly()
                    ->required(),

                TextInput::make('id_pelanggan')
                    ->label('ID Pelanggan')
                    ->required(),

                // Logika QR Code: Otomatis terisi jika ada parameter 'meja' di URL
                TextInput::make('no_meja')
                    ->label('No Meja')
                    ->default(request()->query('meja')) 
                    ->required(),

                // Ambil data dari Master Menu
                Select::make('nama_pesanan')
                    ->label('Pilih Menu')
                    ->options(\App\Models\menu::all()->pluck('nama_menu', 'nama_menu'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $menu = \App\Models\menu::where('nama_menu', $state)->first();
                        if ($menu) {
                            $set('harga_satuan', $menu->harga);
                        }
                    })
                    ->required(),

                TextInput::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->required(),

                TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->reactive(),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $harga = (float) $get('harga_satuan');
                        $qty = (int) $get('jumlah');
                        $set('total_harga', $harga * $qty);
                    }),

                TextInput::make('total_harga')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->required(),

                Textarea::make('catatan')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pemesanan')->label('ID')->sortable(),
                TextColumn::make('no_meja')->label('Meja')->sortable(),
                TextColumn::make('nama_pelanggan')->label('Pelanggan')->searchable(),
                TextColumn::make('nama_pesanan')->label('Menu'),
                TextColumn::make('jumlah')->label('Qty'),
                TextColumn::make('total_harga')->label('Total')->money('idr'),
                TextColumn::make('created_at')->label('Waktu')->dateTime(),
            ])
            ->filters([])
            ->actions([
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
            'index' => Pages\ListPemesanans::route('/'),
            'create' => Pages\CreatePemesanan::route('/create'),
            'edit' => Pages\EditPemesanan::route('/{record}/edit'),
        ];
    }
}
//s
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemesananResource\Pages;
use App\Models\Pemesanan;
use App\Models\menu;
use App\Models\pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class PemesananResource extends Resource
{
    protected static ?string $model = Pemesanan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pemesanan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_pemesanan')
                    ->label('ID Pemesanan')
                    ->default(function () {
                        $count = \App\Models\Pemesanan::count();
                        return 'P-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
                    })
                    ->readOnly()
                    ->required(),

                Select::make('id_pelanggan')
                    ->label('ID Pelanggan')
                    ->options(pelanggan::all()->pluck('id', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $dataPelanggan = pelanggan::find($state); 
                        if ($dataPelanggan) { $set('nama_pelanggan', $dataPelanggan->nama_pelanggan); }
                    })->required(),

                Select::make('id_meja')
                    ->label('No Meja')
                    ->options(collect(range(1, 20))->mapWithKeys(fn ($i) => [$i => 'Meja ' . $i]))
                    ->required(),

                Select::make('nama_pesanan')
                    ->label('Pilih Menu')
                    ->options(menu::all()->pluck('nama_menu', 'nama_menu'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $item = menu::where('nama_menu', $state)->first();
                        if ($item) { $set('harga_satuan', $item->harga); }
                    })->required(),

                TextInput::make('nama_pelanggan')->label('Nama Pelanggan')->readOnly(),
                TextInput::make('harga_satuan')->label('Harga Satuan')->numeric()->prefix('Rp')->readOnly(),
                TextInput::make('jumlah')->label('Jumlah')->numeric()->required()->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $set('total_harga', (float)$get('harga_satuan') * (int)$get('jumlah'));
                    }),
                TextInput::make('total_harga')->label('Total Harga')->numeric()->prefix('Rp')->readOnly(),
                Textarea::make('catatan')->label('Catatan')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pemesanan')->label('ID')->sortable(),
                TextColumn::make('id_meja')->label('No Meja')->formatStateUsing(fn ($state) => "Meja " . $state)->sortable(),
                TextColumn::make('nama_pelanggan')->label('Pelanggan')->searchable(),
                TextColumn::make('nama_pesanan')->label('Menu'),
                TextColumn::make('jumlah')->label('Qty'),
                TextColumn::make('total_harga')->label('Total')->money('idr'),
                TextColumn::make('created_at')->label('Waktu')->dateTime(),
            ])
            // INI YANG DITAMBAHKAN: Tombol New muncul di posisi bawah (atas tabel)
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Pemesanan'),
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
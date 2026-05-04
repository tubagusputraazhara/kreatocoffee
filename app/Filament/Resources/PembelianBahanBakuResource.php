<?php

namespace App\Filament\Resources;

use App\Filament\Resources\pembelianBahanBakuResource\Pages;
use App\Models\pembelianBahanBaku;
use App\Models\bahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;

class pembelianBahanBakuResource extends Resource
{
    protected static ?string $model = pembelianBahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pembelian Bahan Baku';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('no_faktur')
                    ->label('No. Faktur')
                    ->default(fn () => pembelianBahanBaku::getKodeFaktur())
                    ->readonly()
                    ->required(),

                DateTimePicker::make('tgl')
                    ->label('Tanggal Pembelian')
                    ->default(now())
                    ->required(),

                Select::make('bahanBaku_id')
                    ->label('Bahan Baku')
                    // Memastikan kita mengambil ID sebagai string (B001, B002)[cite: 3]
                    ->options(bahanBaku::all()->pluck('nama_bahan', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Mencari data menggunakan ID String
                        $bahan = bahanBaku::where('id', $state)->first(); 
                        if ($bahan) {
                            $set('harga_satuan', $bahan->harga);
                        }
                    }),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $harga = (float) $get('harga_satuan');
                        if ($state && $harga) {
                            $set('total_harga', (int)$state * $harga);
                        }
                    }),

                TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $jumlah = (int) $get('jumlah');
                        if ($state && $jumlah) {
                            $set('total_harga', $jumlah * (float)$state);
                        }
                    }),

                TextInput::make('total_harga')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->readonly()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')
                    ->label('No. Faktur')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tgl')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),

                // Menggunakan relasi yang sudah didefinisikan di Model
                TextColumn::make('bahanBaku.nama_bahan')
                    ->label('Bahan Baku')
                    ->placeholder('Tidak ada data')
                    ->searchable(),

                TextColumn::make('jumlah')
                    ->label('Qty'),

                TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('total_harga')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable(),
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
            'index' => Pages\ListPembelianBahanBakus::route('/'),
            'create' => Pages\CreatePembelianBahanBaku::route('/create'),
            'edit' => Pages\EditPembelianBahanBaku::route('/{record}/edit'),
        ];
    }
}
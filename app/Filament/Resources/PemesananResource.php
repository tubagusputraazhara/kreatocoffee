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

                        if ($dataPelanggan) {
                            $set('nama_pelanggan', $dataPelanggan->nama_pelanggan);
                        }
                    })
                    ->required(),

                Select::make('id_meja')
                    ->label('No Meja')
                    ->options(
                        collect(range(1, 20))
                            ->mapWithKeys(fn ($i) => [$i => 'Meja ' . $i])
                    )
                    ->required(),

                Select::make('nama_pesanan')
                    ->label('Pilih Menu')
                    ->options(menu::all()->pluck('nama_menu', 'nama_menu'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {

                        $item = menu::where('nama_menu', $state)->first();

                        if ($item) {
                            $set('harga_satuan', $item->harga);
                        }
                    })
                    ->required(),

                TextInput::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->readOnly(),

                TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly(),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $set(
                            'total_harga',
                            (float) $get('harga_satuan') * (int) $get('jumlah')
                        );
                    }),

                TextInput::make('total_harga')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly(),

                Textarea::make('catatan')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pemesanan')
                    ->label('ID')
                    ->sortable(),

                // Gabungan: fallback ke id_meja atau no_meja
                TextColumn::make('no_meja')
                    ->label('No Meja')
                    ->getStateUsing(fn ($record) => $record->id_meja
                        ? 'Meja ' . $record->id_meja
                        : ($record->no_meja ?? '-'))
                    ->sortable(),

                // Gabungan: fallback ke nama_pelanggan atau nama_pemesan
                TextColumn::make('nama_pemesan')
                    ->label('Pelanggan')
                    ->getStateUsing(fn ($record) => $record->nama_pelanggan
                        ?? $record->nama_pemesan
                        ?? '-')
                    ->searchable(),

                // Gabungan: tampilkan detail menu jika ada, fallback ke nama_pesanan
                TextColumn::make('details_summary')
                    ->label('Menu')
                    ->getStateUsing(function ($record) {
                        if ($record->details && $record->details->count() > 0) {
                            return $record->details
                                ->map(fn ($d) => $d->nama_menu . ' x' . $d->qty)
                                ->implode(', ');
                        }
                        return $record->nama_pesanan ?? '-';
                    }),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('idr'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'selesai' => 'success',
                        'batal'   => 'danger',
                        default   => 'gray',
                    }),

                TextColumn::make('sumber')
                    ->label('Sumber')
                    ->badge()
                    ->color(fn ($state) => $state === 'customer' ? 'info' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime(),
            ])

            ->headerActions([
                // Dari versi temanmu: tombol shortcut ke halaman kasir
                Tables\Actions\Action::make('kasir')
                    ->label('Buka Kasir')
                    ->icon('heroicon-o-calculator')
                    ->url(url('/kasir')),

                // Dari versi kamu: tombol buat pemesanan baru standar Filament
                Tables\Actions\CreateAction::make()
                    ->label('New Pemesanan'),
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
            'index'  => Pages\ListPemesanans::route('/'),
            'create' => Pages\CreatePemesanan::route('/create'),
            'edit'   => Pages\EditPemesanan::route('/{record}/edit'),
        ];
    }
}
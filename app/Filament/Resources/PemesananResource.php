<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemesananResource\Pages;
use App\Models\Pemesanan;
use App\Services\JurnalService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PemesananResource extends Resource
{
    protected static ?string $model = Pemesanan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pemesanan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('kode_pemesanan')->readOnly(),
            Forms\Components\TextInput::make('nama_pemesan')->required(),
            Forms\Components\Select::make('no_meja')
                ->options(collect(range(1, 30))->mapWithKeys(fn ($i) => [$i => 'Meja ' . $i])),
            Forms\Components\TextInput::make('total_harga')->prefix('Rp')->numeric(),
            Forms\Components\Select::make('status')
                ->label('Status Pembayaran')
                ->options([
                    'belum_lunas' => 'Belum Lunas',
                    'lunas'       => 'Lunas',
                    'batal'       => 'Batal',
                ]),
            Forms\Components\Select::make('status_pesanan')
                ->label('Status Pesanan')
                ->options([
                    'diproses'   => 'Sedang Dimasak',
                    'diantarkan' => 'Sudah Diantarkan',
                    'selesai'    => 'Selesai',
                ]),
            Forms\Components\Textarea::make('catatan')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pemesanan')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('kode_pemesanan')
                    ->label('Kode')
                    ->weight('bold')
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('no_meja')
                    ->label('No Meja')
                    ->getStateUsing(fn ($record) => $record->no_meja ?? '-')
                    ->sortable(),

                TextColumn::make('nama_pemesan')
                    ->label('Pelanggan')
                    ->searchable(),

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
                    ->money('idr')
                    ->weight('bold')
                    ->color('success'),

                // ✅ Status pembayaran
                TextColumn::make('status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'belum_lunas' => 'warning',
                        'lunas'       => 'success',
                        'batal'       => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'belum_lunas' => 'Belum Lunas',
                        'lunas'       => 'Lunas',
                        'batal'       => 'Batal',
                        default       => $state,
                    }),

                // ✅ Status pesanan
                TextColumn::make('status_pesanan')
                    ->label('Status Pesanan')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'diproses'   => 'warning',
                        'diantarkan' => 'info',
                        'selesai'    => 'success',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'diproses'   => 'Sedang Dimasak',
                        'diantarkan' => 'Sudah Diantarkan',
                        'selesai'    => 'Selesai',
                        default      => $state,
                    }),

                TextColumn::make('sumber')
                    ->label('Sumber')
                    ->badge()
                    ->color(fn ($state) => $state === 'customer' ? 'info' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordClasses(fn () => 'border-l-4 border-primary-500')

            ->headerActions([
                Tables\Actions\Action::make('kasir')
                    ->label('Buka Kasir')
                    ->icon('heroicon-o-calculator')
                    ->color('primary')
                    ->url(url('/kasir')),
            ])

            ->filters([])

            ->actions([
                // ✅ Tandai Lunas Cash — muncul kalau belum lunas
                Tables\Actions\Action::make('lunas_cash')
                    ->label('Tandai Lunas (Cash)')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'belum_lunas')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran Cash')
                    ->modalDescription('Pastikan pembayaran cash sudah diterima sebelum mengkonfirmasi.')
                    ->action(function ($record) {
                        $record->update(['status' => 'lunas']);

                        // ✅ Trigger jurnal otomatis kalau belum dibuat
                        if (!$record->jurnal_dibuat) {
                            JurnalService::jurnalPenjualan($record);
                            $record->update(['jurnal_dibuat' => true]);
                        }
                    }),

                // ✅ Tombol ubah status pesanan
                Tables\Actions\Action::make('diantarkan')
                    ->label('Sudah Diantarkan')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn ($record) => $record->status_pesanan === 'diproses')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['status_pesanan' => 'diantarkan'])),

                Tables\Actions\Action::make('selesai_pesanan')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status_pesanan === 'diantarkan')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['status_pesanan' => 'selesai'])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
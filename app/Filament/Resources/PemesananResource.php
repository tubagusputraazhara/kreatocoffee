<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemesananResource\Pages;
use App\Models\Pemesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;

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
                ->options(collect(range(1, 20))->mapWithKeys(fn ($i) => [$i => 'Meja ' . $i])),
            Forms\Components\TextInput::make('total_harga')->prefix('Rp')->numeric(),
            Forms\Components\Textarea::make('catatan')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('kode_pemesanan')->weight('bold')->color('primary'),
                    TextColumn::make('nama_pemesan')->color('gray'),
                    TextColumn::make('no_meja')->formatStateUsing(fn ($state) => "Meja " . $state)->size('sm'),
                ])->space(1),

                TextColumn::make('details.nama_menu')
                    ->label('Pesanan')
                    ->listWithLineBreaks()
                    ->bulleted(),

                TextColumn::make('details.qty')
                    ->label('Qty')
                    ->alignCenter(),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('idr')
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped() // Membuat tabel berwarna selang-seling agar menarik
            ->recordClasses(fn () => 'border-l-4 border-primary-500') // Memberikan aksen warna di samping baris
            ->headerActions([
                Tables\Actions\Action::make('kasir')
                    ->label('New Pemesanan')
                    ->color('primary')
                    ->icon('heroicon-o-plus-circle')
                    ->url(url('/kasir')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
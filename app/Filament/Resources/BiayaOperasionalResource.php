<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaOperasionalResource\Pages;
use App\Models\BiayaOperasional;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BiayaOperasionalResource extends Resource
{
    protected static ?string $model = BiayaOperasional::class;

    // Ikon uang di sidebar admin
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Input Biaya Operasional')
                    ->description('Catat pengeluaran harian KreatoCoffee tanpa relasi COA.')
                    ->schema([
                        Forms\Components\DatePicker::make('tgl_biaya')
                            ->label('Tanggal Transaksi')
                            ->default(now())
                            ->required(),
                        
                        Forms\Components\TextInput::make('nama_biaya')
                            ->label('Keterangan Biaya')
                            ->placeholder('Contoh: Biaya Listrik atau Pembelian Bahan Baku')
                            ->required(),

                        Forms\Components\Select::make('id_karyawan')
                            ->label('Karyawan/PIC')
                            ->relationship('karyawan', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('jumlah_biaya')
                            ->label('Nominal (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\FileUpload::make('bukti_bayar')
                            ->label('Foto Bukti/Struk')
                            ->image()
                            ->directory('biaya-operasional'),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Catatan Tambahan')
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tgl_biaya')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_biaya')
                    ->label('Keterangan Biaya')
                    ->searchable(),

                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('PIC'),

                Tables\Columns\TextColumn::make('jumlah_biaya')
                    ->label('Total Nominal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('bukti_bayar')
                    ->label('Bukti'),
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
            'index' => Pages\ListBiayaOperasionals::route('/'),
            'create' => Pages\CreateBiayaOperasional::route('/create'),
            'edit' => Pages\EditBiayaOperasional::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Models\Penggajian;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Komponen Form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

// Komponen Table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

// Untuk set nilai otomatis
use Filament\Forms\Get;
use Filament\Forms\Set;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Penggajian';

    protected static ?string $pluralModelLabel = 'Penggajian';

    protected static ?string $modelLabel = 'Penggajian';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Penggajian')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('id_penggajian')
                                ->label('ID Penggajian')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(50)
                                ->placeholder('Contoh: GJI001'),

                            Select::make('id_karyawan')
                                ->label('Karyawan')
                                ->required()
                                ->searchable()
                                ->live()
                                // Tampilkan format: "KYN001 - Budi Santoso"
                                ->options(
                                    Karyawan::all()->mapWithKeys(fn ($k) => [
                                        $k->id_karyawan => $k->id_karyawan . ' - ' . $k->nama
                                    ])
                                )
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                    if ($state) {
                                        $karyawan = Karyawan::find($state);
                                        if ($karyawan) {
                                            // Otomatis isi gaji pokok dari data karyawan
                                            $set('gaji_pokok', $karyawan->gaji);
                                            // Hitung ulang gaji bersih
                                            $tunjangan = (float) $get('tunjangan') ?? 0;
                                            $potongan  = (float) $get('potongan')  ?? 0;
                                            $set('gaji_bersih', ($karyawan->gaji + $tunjangan) - $potongan);
                                        } else {
                                            $set('gaji_pokok', 0);
                                            $set('gaji_bersih', 0);
                                        }
                                    }
                                }),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('bulan')
                                ->label('Bulan')
                                ->required()
                                ->options(Penggajian::namaBulan()),

                            TextInput::make('tahun')
                                ->label('Tahun')
                                ->required()
                                ->numeric()
                                ->minValue(2000)
                                ->maxValue(2100)
                                ->default(now()->year)
                                ->placeholder('Contoh: 2024'),
                        ]),
                    ]),

                Section::make('Detail Gaji')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('gaji_pokok')
                                ->label('Gaji Pokok')
                                ->required()
                                ->numeric()
                                ->prefix('Rp')
                                ->minValue(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                    $gaji_pokok = (float) $state ?? 0;
                                    $tunjangan  = (float) $get('tunjangan') ?? 0;
                                    $potongan   = (float) $get('potongan')  ?? 0;
                                    $set('gaji_bersih', ($gaji_pokok + $tunjangan) - $potongan);
                                }),

                            TextInput::make('tunjangan')
                                ->label('Tunjangan')
                                ->numeric()
                                ->prefix('Rp')
                                ->minValue(0)
                                ->default(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                    $gaji_pokok = (float) $get('gaji_pokok') ?? 0;
                                    $tunjangan  = (float) $state ?? 0;
                                    $potongan   = (float) $get('potongan')  ?? 0;
                                    $set('gaji_bersih', ($gaji_pokok + $tunjangan) - $potongan);
                                }),

                            TextInput::make('potongan')
                                ->label('Potongan')
                                ->numeric()
                                ->prefix('Rp')
                                ->minValue(0)
                                ->default(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                    $gaji_pokok = (float) $get('gaji_pokok') ?? 0;
                                    $tunjangan  = (float) $get('tunjangan') ?? 0;
                                    $potongan   = (float) $state ?? 0;
                                    $set('gaji_bersih', ($gaji_pokok + $tunjangan) - $potongan);
                                }),
                        ]),

                        TextInput::make('gaji_bersih')
                            ->label('Gaji Bersih')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated() // tetap disimpan meski disabled
                            ->default(0),
                    ]),

                Section::make('Status & Pembayaran')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('Status')
                                ->required()
                                ->options([
                                    'Pending' => 'Pending',
                                    'Dibayar' => 'Dibayar',
                                    'Ditolak' => 'Ditolak',
                                ])
                                ->default('Pending'),

                            DatePicker::make('tanggal_bayar')
                                ->label('Tanggal Bayar')
                                ->nullable(),
                        ]),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->nullable()
                            ->rows(3)
                            ->placeholder('Catatan tambahan (opsional)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_penggajian')
                    ->label('ID Penggajian')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('karyawan.nama')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->formatStateUsing(fn (int $state): string => Penggajian::namaBulan()[$state] ?? $state)
                    ->sortable(),

                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),

                TextColumn::make('gaji_pokok')
                    ->label('Gaji Pokok')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tunjangan')
                    ->label('Tunjangan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('potongan')
                    ->label('Potongan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('gaji_bersih')
                    ->label('Gaji Bersih')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Dibayar' => 'success',
                        'Ditolak' => 'danger',
                        default   => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Dibayar' => 'Dibayar',
                        'Ditolak' => 'Ditolak',
                    ]),

                SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options(Penggajian::namaBulan()),

                SelectFilter::make('id_karyawan')
                    ->label('Karyawan')
                    ->options(Karyawan::all()->pluck('nama', 'id_karyawan'))
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit'   => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
}
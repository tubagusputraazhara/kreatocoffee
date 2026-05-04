<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaResource\Pages;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Tambahan untuk komponen input form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;

// Tambahan untuk komponen kolom
use Filament\Tables\Columns\TextColumn;

class CoaResource extends Resource
{
    protected static ?string $model = Coa::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'COA';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $pluralLabel = 'COA';

    protected static ?string $modelLabel = 'COA';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_akun')
                    ->label('Kode Akun')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('contoh: 1-001')
                    ->maxLength(20),

                TextInput::make('nama_akun')
                    ->label('Nama Akun')
                    ->required()
                    ->maxLength(100),

                Select::make('kategori_akun')
                    ->label('Kategori Akun')
                    ->options([
                        'Aset'       => 'Aset',
                        'Kewajiban'  => 'Kewajiban',
                        'Modal'      => 'Modal',
                        'Pendapatan' => 'Pendapatan',
                        'Beban'      => 'Beban',
                    ])
                    ->required(),

                Select::make('jenis_akun')
                    ->label('Jenis Akun')
                    ->options([
                        'Debit'  => 'Debit',
                        'Kredit' => 'Kredit',
                    ])
                    ->required(),

                TextInput::make('saldo_normal')
                    ->label('Saldo Normal')
                    ->numeric()
                    ->prefix('Rp')
                    ->step(1)
                    ->default(0),

                DatePicker::make('tanggal_dibuat')
                    ->label('Tanggal Dibuat')
                    ->required(),

                Select::make('status_akun')
                    ->label('Status Akun')
                    ->options([
                        'Aktif'       => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ])
                    ->default('Aktif')
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(500)
                    ->rows(3)
                    ->columnSpan(2)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_coa')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('kode_akun')
                    ->label('Kode Akun')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('nama_akun')
                    ->label('Nama Akun')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kategori_akun')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aset'       => 'success',
                        'Kewajiban'  => 'danger',
                        'Modal'      => 'warning',
                        'Pendapatan' => 'info',
                        'Beban'      => 'gray',
                        default      => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('jenis_akun')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Debit'  => 'success',
                        'Kredit' => 'danger',
                        default  => 'gray',
                    }),

                TextColumn::make('saldo_normal')
                    ->label('Saldo Normal')
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: '.',
                    )
                    ->prefix('Rp ')
                    ->sortable(),

                TextColumn::make('tanggal_dibuat')
                    ->label('Tanggal Dibuat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status_akun')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif'       => 'success',
                        'Tidak Aktif' => 'danger',
                        default       => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_akun')
                    ->label('Kategori Akun')
                    ->options([
                        'Aset'       => 'Aset',
                        'Kewajiban'  => 'Kewajiban',
                        'Modal'      => 'Modal',
                        'Pendapatan' => 'Pendapatan',
                        'Beban'      => 'Beban',
                    ]),

                Tables\Filters\SelectFilter::make('status_akun')
                    ->label('Status Akun')
                    ->options([
                        'Aktif'       => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ]),
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
            ])
            ->emptyStateHeading('Belum ada data COA')
            ->emptyStateDescription('Buat akun pertama untuk memulai.');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit'   => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}
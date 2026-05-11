<?php

namespace App\Filament\Resources;

use App\Filament\Resources\pembelianBahanBakuResource\Pages;

use App\Models\pembelianBahanBaku;
use App\Models\bahanBaku;

use App\Filament\Exports\PembelianBahanBakuExporter;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;

use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\Action;

use Barryvdh\DomPDF\Facade\Pdf;

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
                    ->options(
                        bahanBaku::all()->pluck('nama_bahan', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()

                    ->afterStateUpdated(function ($state, callable $set) {

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

                    ->afterStateUpdated(function (
                        $state,
                        callable $get,
                        callable $set
                    ) {

                        $harga = (float) $get('harga_satuan');

                        if ($state && $harga) {

                            $set(
                                'total_harga',
                                (int) $state * $harga
                            );

                        }
                    }),

                TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->reactive()

                    ->afterStateUpdated(function (
                        $state,
                        callable $get,
                        callable $set
                    ) {

                        $jumlah = (int) $get('jumlah');

                        if ($state && $jumlah) {

                            $set(
                                'total_harga',
                                $jumlah * (float) $state
                            );

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

            ->headerActions([

                ExportAction::make()
                    ->exporter(PembelianBahanBakuExporter::class)
                    ->color('success'),

                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')

                    ->action(function () {

                        $pembelianBahanBakus =
                            pembelianBahanBaku::all();

                        $pdf = Pdf::loadView(
                            'pdf.PembelianBahanBaku',
                            [
                                'pembelianBahanBakus' =>
                                    $pembelianBahanBakus
                            ]
                        );

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'data-pembelian-bahan-baku.pdf'
                        );
                    }),
            ])

            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

            ])

            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

                ExportBulkAction::make()
                    ->exporter(
                        PembelianBahanBakuExporter::class
                    ),

            ]);
    }

    public static function getPages(): array
    {
        return [

            'index' =>
                Pages\ListPembelianBahanBakus::route('/'),

            'create' =>
                Pages\CreatePembelianBahanBaku::route('/create'),

            'edit' =>
                Pages\EditPembelianBahanBaku::route('/{record}/edit'),

        ];
    }
}

//
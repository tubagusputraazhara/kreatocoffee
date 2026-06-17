<?php

namespace App\Filament\Resources;

use App\Filament\Exports\BiayaOperasionalExporter;
use App\Filament\Resources\BiayaOperasionalResource\Pages;
use App\Models\BiayaOperasional;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\Action;

class BiayaOperasionalResource extends Resource
{
    protected static ?string $model = BiayaOperasional::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Biaya Operasional')
                    ->description('')
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

                Tables\Columns\TextColumn::make('karyawan.nama')
                    ->label('PIC'),

                Tables\Columns\TextColumn::make('jumlah_biaya')
                    ->label('Total Nominal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('bukti_bayar')
                    ->label('Bukti'),
            ])
            ->filters([])
            ->headerActions([
                // tombol export csv dan excel
                ExportAction::make()->exporter(BiayaOperasionalExporter::class)->color('success'),
                // tombol unduh PDF
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function () {
                        $biayaOperasionals = BiayaOperasional::with('karyawan')->get();
                        $pdf = Pdf::loadView('pdf.BiayaOperasional', [
                            'biayaOperasionals' => $biayaOperasionals
                        ]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'daftar-biaya-operasional.pdf'
                        );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                // tambahan export excel bulk
                ExportBulkAction::make()->exporter(BiayaOperasionalExporter::class)
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
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
    protected static ?string $navigationLabel = 'Biaya Operasional';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Biaya Operasional')
                    ->description('Catat seluruh pengeluaran operasional café.')
                    ->schema([
                        Forms\Components\DatePicker::make('tgl_biaya')
                            ->label('Tanggal Transaksi')
                            ->default(now())
                            ->required(),

                        Forms\Components\TextInput::make('nama_biaya')
                            ->label('Keterangan Biaya')
                            ->placeholder('Contoh: Biaya listrik, gas, pembelian bahan baku')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('id_karyawan')
                            ->label('Karyawan / PIC')
                            ->relationship('karyawan', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('jumlah_biaya')
                            ->label('Nominal')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\FileUpload::make('bukti_bayar')
                            ->label('Foto Bukti / Struk')
                            ->image()
                            ->directory('biaya-operasional'),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Catatan Tambahan')
                            ->placeholder('Opsional')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Biaya Operasional')
            ->description('Kelola data pengeluaran operasional café.')
            ->searchPlaceholder('Cari keterangan biaya / PIC...')
            ->emptyStateHeading('Belum ada data biaya operasional')
            ->emptyStateDescription('Data biaya operasional akan muncul setelah ada transaksi yang dicatat.')
            ->defaultSort('tgl_biaya', 'desc')

            ->columns([
                Tables\Columns\TextColumn::make('tgl_biaya')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn ($record) => \Carbon\Carbon::parse($record->tgl_biaya)->diffForHumans()),

                Tables\Columns\TextColumn::make('nama_biaya')
                    ->label('Keterangan Biaya')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->keterangan ?: 'Tidak ada catatan tambahan'),

                Tables\Columns\TextColumn::make('karyawan.nama')
                    ->label('PIC')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('jumlah_biaya')
                    ->label('Total Nominal')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('danger'),

                Tables\Columns\ImageColumn::make('bukti_bayar')
                    ->label('Bukti')
                    ->square(),
            ])

            ->filters([
                Tables\Filters\Filter::make('hari_ini')
                    ->label('Hari ini')
                    ->query(fn ($query) => $query->whereDate('tgl_biaya', now())),

                Tables\Filters\Filter::make('minggu_ini')
                    ->label('Minggu ini')
                    ->query(fn ($query) => $query->whereBetween('tgl_biaya', [
                        now()->startOfWeek(),
                        now()->endOfWeek(),
                    ])),

                Tables\Filters\Filter::make('bulan_ini')
                    ->label('Bulan ini')
                    ->query(fn ($query) => $query->whereMonth('tgl_biaya', now()->month)
                        ->whereYear('tgl_biaya', now()->year)),
            ])

            ->headerActions([
                ExportAction::make()
                    ->exporter(BiayaOperasionalExporter::class)
                    ->label('Export Excel')
                    ->color('success'),

                Action::make('previewPdf')
                    ->label('Lihat PDF')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Preview Daftar Biaya Operasional')
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function () {
                        $biayaOperasionals = BiayaOperasional::with('karyawan')->get();
                        $pdf = Pdf::loadView('pdf.BiayaOperasional', [
                            'biayaOperasionals' => $biayaOperasionals
                        ])->setPaper('a4', 'portrait');

                        $base64 = base64_encode($pdf->output());

                        return view('filament.modals.pdf-preview', ['base64' => $base64]);
                    }),

                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function () {
                        $biayaOperasionals = BiayaOperasional::with('karyawan')->get();
                        $pdf = Pdf::loadView('pdf.BiayaOperasional', [
                            'biayaOperasionals' => $biayaOperasionals
                        ])->setPaper('a4', 'portrait');

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'daftar-biaya-operasional.pdf'
                        );
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()
                    ->exporter(BiayaOperasionalExporter::class),
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
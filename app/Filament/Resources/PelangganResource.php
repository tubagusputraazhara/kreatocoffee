<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PelangganExporter;
use App\Filament\Resources\PelangganResource\Pages;
use App\Models\pelanggan;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction as TableExportBulkAction;
use Filament\Tables\Actions\Action;

class PelangganResource extends Resource
{
    protected static ?string $model = pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pelanggan')
                    ->description('Kelola informasi pelanggan café.')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pelanggan')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('no_hp')
                            ->label('No HP')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Pelanggan')
            ->description('Kelola data pelanggan café yang terdaftar.')
            ->searchPlaceholder('Cari nama pelanggan / ID...')
            ->emptyStateHeading('Belum ada data pelanggan')
            ->emptyStateDescription('Data pelanggan akan muncul setelah ada pelanggan yang terdaftar.')
            ->defaultSort('created_at', 'desc')

            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => 'No HP: ' . $record->no_hp),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Bergabung')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->created_at?->diffForHumans()),
            ])

            ->filters([
                Tables\Filters\Filter::make('hari_ini')
                    ->label('Hari ini')
                    ->query(fn ($query) => $query->whereDate('created_at', now())),

                Tables\Filters\Filter::make('minggu_ini')
                    ->label('Minggu ini')
                    ->query(fn ($query) => $query->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek(),
                    ])),

                Tables\Filters\Filter::make('bulan_ini')
                    ->label('Bulan ini')
                    ->query(fn ($query) => $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)),
            ])

            ->headerActions([
                ExportAction::make()
                    ->exporter(PelangganExporter::class)
                    ->label('Export Excel')
                    ->color('success'),

                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function () {
                        $pelanggans = pelanggan::all();
                        $pdf = Pdf::loadView('pdf.pelanggan', ['pelanggans' => $pelanggans]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'daftar-pelanggan.pdf'
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
                TableExportBulkAction::make()
                    ->exporter(PelangganExporter::class),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\Createpelanggan::route('/create'),
            'edit' => Pages\Editpelanggan::route('/{record}/edit'),
        ];
    }
}
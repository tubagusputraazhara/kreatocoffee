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
use Filament\Actions\Exports\ExportBulkAction;
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
                Forms\Components\TextInput::make('nama_pelanggan')
                    ->required(),

                Forms\Components\TextInput::make('no_hp')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_hp'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat'),
            ])
            ->headerActions([
                // tombol export csv dan excel
                ExportAction::make()->exporter(PelangganExporter::class)->color('success'),
                // tombol unduh PDF
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
                TableExportBulkAction::make()->exporter(PelangganExporter::class)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Listpelanggans::route('/'),
            'create' => Pages\Createpelanggan::route('/create'),
            'edit' => Pages\Editpelanggan::route('/{record}/edit'),
        ];
    }
}
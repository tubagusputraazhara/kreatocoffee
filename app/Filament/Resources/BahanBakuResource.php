<?php

namespace App\Filament\Resources;

use App\Filament\Resources\bahanBakuResource\Pages;
use App\Models\bahanBaku;

use App\Filament\Exports\BahanBakuExporter;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\Action;

use Barryvdh\DomPDF\Facade\Pdf;

class bahanBakuResource extends Resource
{
    protected static ?string $model = bahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Bahan Baku';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $pluralLabel = 'Bahan Baku';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('nama_bahan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('kategori')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('satuan')
                    ->required()
                    ->placeholder('kg, liter, pcs'),

                Forms\Components\TextInput::make('stok')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('ID Bahan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_bahan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('stok')
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan'),

                Tables\Columns\TextColumn::make('harga')
                    ->money('IDR', true),

            ])

            ->filters([
                //
            ])

            ->headerActions([

                ExportAction::make()
                    ->exporter(BahanBakuExporter::class)
                    ->color('success'),

                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')

                    ->action(function () {

                        $bahanBakus = bahanBaku::all();

                        $pdf = Pdf::loadView(
                            'pdf.BahanBaku',
                            ['bahanBakus' => $bahanBakus]
                        );

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'data-bahan-baku.pdf'
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
                    ->exporter(BahanBakuExporter::class),

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

            'index' => Pages\ListbahanBakus::route('/'),

            'create' => Pages\CreatebahanBaku::route('/create'),

            'edit' => Pages\EditbahanBaku::route('/{record}/edit'),

        ];
    }
}
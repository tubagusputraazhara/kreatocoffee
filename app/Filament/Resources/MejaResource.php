<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Mejaresource\Pages;
use App\Models\Meja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class Mejaresource extends Resource
{
    protected static ?string $model = Meja::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationLabel = 'Meja';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $pluralLabel = 'Meja';
    protected static ?string $modelLabel = 'Meja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_meja')
                    ->label('Nama Meja')
                    ->required()
                    ->maxLength(100),

                TextInput::make('id_meja')
                    ->label('ID Meja')
                    ->default(fn () => \App\Models\Meja::getIdMeja())
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->readonly(),

                TextInput::make('kapasitas')
                    ->label('Kapasitas')
                    ->numeric()
                    ->default(4)
                    ->minValue(1)
                    ->maxValue(20),

                Select::make('lokasi')
                    ->label('Lokasi')
                    ->options([
                        'indoor'  => 'Indoor',
                        'outdoor' => 'Outdoor',
                    ])
                    ->default('indoor')
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(500)
                    ->rows(3),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_meja')
                    ->label('Nama Meja')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('id_meja')
                    ->label('ID Meja')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                ImageColumn::make('qr_code_path')
                    ->label('QR Code')
                    ->size(50)
                    ->square(),

                TextColumn::make('kapasitas')
                    ->label('Kapasitas')
                    ->suffix(' orang')
                    ->sortable(),

                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'indoor'  => 'info',
                        'outdoor' => 'success',
                        default   => 'gray',
                    }),

                // Diganti dari IconColumn ke ToggleColumn
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Preview QR → popup modal
                Action::make('preview_qr')
                    ->label('Preview QR')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'QR Code - ' . $record->nama_meja)
                    ->modalContent(fn ($record) => view(
                        'filament.modals.preview-qr',
                        ['meja' => $record]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // Download QR langsung
                Action::make('download_qr')
                    ->label('Download QR')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn ($record) => response()->download(
                        storage_path('app/public/' . $record->qr_code_path),
                        'QRCode-' . $record->nama_meja . '.png'
                    )),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data meja')
            ->emptyStateDescription('Buat meja pertama untuk memulai.');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMejas::route('/'),
            'create' => Pages\CreateMeja::route('/create'),
            'edit'   => Pages\EditMeja::route('/{record}/edit'),
        ];
    }
}
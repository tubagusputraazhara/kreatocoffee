<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Mejaresource\Pages;
use App\Filament\Resources\Mejaresource\RelationManagers;
use App\Models\Meja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

class Mejaresource extends Resource
{
    protected static ?string $model = Meja::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Meja';
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
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                FileUpload::make('foto_meja')
                    ->label('Foto Meja')
                    ->image()
                    ->imageEditor()
                    ->directory('meja')
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('300')
                    ->imageResizeTargetHeight('300'),

                FileUpload::make('qr_code_path')
                    ->label('QR Code')
                    ->image()
                    ->directory('qr-codes')
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('300')
                    ->imageResizeTargetHeight('300'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'ditempati' => 'Ditempati',
                        'reservasi' => 'Reservasi',
                        'maintenance' => 'Maintenance',
                    ])
                    ->default('tersedia')
                    ->required(),

                TextInput::make('kapasitas')
                    ->label('Kapasitas')
                    ->numeric()
                    ->default(4)
                    ->minValue(1)
                    ->maxValue(20),

                Select::make('lokasi')
                    ->label('Lokasi')
                    ->options([
                        'indoor' => 'Indoor',
                        'outdoor' => 'Outdoor',
                        'vip' => 'VIP',
                        'family' => 'Family',
                    ])
                    ->default('indoor')
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(500)
                    ->rows(3),

                TextInput::make('harga_minimum')
                    ->label('Harga Minimum')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),

                DatePicker::make('tanggal_perawatan')
                    ->label('Tanggal Perawatan')
                    ->displayFormat('d/m/Y'),

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

                ImageColumn::make('foto_meja')
                    ->label('Foto')
                    ->size(60)
                    ->circular(),

                ImageColumn::make('qr_code_path')
                    ->label('QR Code')
                    ->size(50)
                    ->square(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'tersedia' => 'success',
                        'ditempati' => 'danger',
                        'reservasi' => 'warning',
                        'maintenance' => 'gray',
                    ]),

                TextColumn::make('kapasitas')
                    ->label('Kapasitas')
                    ->suffix(' orang')
                    ->sortable(),

                BadgeColumn::make('lokasi')
                    ->label('Lokasi')
                    ->colors([
                        'indoor' => 'blue',
                        'outdoor' => 'green',
                        'vip' => 'purple',
                        'family' => 'indigo',
                    ]),

                TextColumn::make('harga_minimum')
                    ->label('Min. Order')
                    ->money('IDR')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMejas::route('/'),
            'create' => Pages\CreateMeja::route('/create'),
            'edit' => Pages\EditMeja::route('/{record}/edit'),
        ];
    }
}
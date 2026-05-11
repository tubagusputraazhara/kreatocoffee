<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Import Komponen
use App\Filament\Exports\MenuExporter;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Master Data';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_menu')
                    ->label('ID Menu')
                    ->default(function () {
                        $count = \App\Models\Menu::count();
                        $nextNumber = $count + 1;
                        return 'M' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                    })
                    ->readOnly()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama_menu')->required(),
                TextInput::make('harga')->numeric()->prefix('Rp')->required(),
                Select::make('kategori')
                    ->options(['Makanan' => 'Makanan', 'Minuman' => 'Minuman'])
                    ->required(),
                FileUpload::make('gambar')->directory('menu-images')->image()->required(),
                Textarea::make('deskripsi')->label('Deskripsi Menu')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_menu')->label('ID Menu')->sortable(),
                TextColumn::make('nama_menu')->label('Nama Menu')->searchable(),
                TextColumn::make('harga')->label('Harga')->money('idr'),
                TextColumn::make('kategori')->label('Kategori'),
                ImageColumn::make('gambar')->label('Gambar')->size(50),
                TextColumn::make('deskripsi')->label('Deskripsi')->limit(50),
            ])
            // INI YANG DITAMBAHKAN: Tombol New Menu muncul di area tabel (posisi bawah)
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Menu'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()->exporter(MenuExporter::class)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Filament\Resources\KaryawanResource\RelationManagers;
use App\Models\Karyawan;
use App\Models\Jabatan; // tambahan: import model Jabatan untuk dropdown
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// tambahan untuk komponen input form
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;
// tambahan untuk komponen kolom
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Grid;

// tambahan untuk karyawan exporter
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\KaryawanExporter;

// tambahan untuk tombol unduh pdf
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Karyawan';
    protected static ?string $pluralModelLabel = 'Karyawan';
    protected static ?string $modelLabel = 'Karyawan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_Karyawan')
                    ->label('ID Karyawan')
                    ->default(function () {
                        $count = \App\Models\Karyawan::count();
                        $nextNumber = $count + 1;
                        return 'KYN' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
                    })
                    ->readOnly()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->label('Nama')
                    ->required(),

                Radio::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                // tambahan: ganti TextInput jabatan menjadi Select dari master data Jabatan
                Select::make('jabatan')
                    ->label('Jabatan')
                    ->options(
                        Jabatan::all()->pluck('jabatan', 'jabatan') // value & label = nama jabatan
                    )
                    ->searchable()
                    ->required()
                    ->placeholder('Pilih Jabatan'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_karyawan')
                    ->label('ID Karyawan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->sortable(),

                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->sortable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->sortable(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            // tombol header tambahan
            ->headerActions([
                // tombol export csv dan excel
                ExportAction::make()->exporter(KaryawanExporter::class)->color('success'),

                // tombol unduh PDF
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $karyawans = Karyawan::all();

                        $pdf = Pdf::loadView('pdf.karyawan', ['karyawans' => $karyawans]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'daftar-karyawan.pdf'
                        );
                    })
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

                // tambahan export excel bulk
                ExportBulkAction::make()->exporter(KaryawanExporter::class)
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
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
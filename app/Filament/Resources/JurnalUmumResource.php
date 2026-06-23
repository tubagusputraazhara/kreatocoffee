<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalUmumResource\Pages;
use App\Models\JurnalUmum;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class JurnalUmumResource extends Resource
{
    protected static ?string $model = JurnalUmum::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Jurnal Umum';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $pluralLabel = 'Jurnal Umum';

    protected static ?string $modelLabel = 'Jurnal Umum';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([]);
    }

    // ✅ Infolist untuk modal View
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Informasi Jurnal')
                ->columns(2)
                ->schema([
                    TextEntry::make('nomor_jurnal')->label('Nomor Jurnal'),
                    TextEntry::make('tanggal_jurnal')->label('Tanggal')->date('d M Y'),
                    TextEntry::make('ref')->label('Referensi')->badge()->color('info'),
                    TextEntry::make('keterangan')->label('Keterangan')->columnSpanFull(),
                ]),

            Section::make('Detail Jurnal')
                ->schema([
                    RepeatableEntry::make('detailJurnal')
                        ->label('')
                        ->columns(3)
                        ->schema([
                            TextEntry::make('coa.nama_akun')->label('Akun'),
                            TextEntry::make('debit')->label('Debit')->money('IDR')->color('success'),
                            TextEntry::make('kredit')->label('Kredit')->money('IDR')->color('danger'),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_jurnal')
                    ->label('Nomor Jurnal')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_jurnal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('ref')
                    ->label('Ref')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('total_debit')
                    ->label('Total Debit')
                    ->getStateUsing(fn ($record) =>
                        'Rp ' . number_format(
                            $record->detailJurnal->sum('debit'), 0, ',', '.'
                        )
                    )
                    ->color('success'),

                TextColumn::make('total_kredit')
                    ->label('Total Kredit')
                    ->getStateUsing(fn ($record) =>
                        'Rp ' . number_format(
                            $record->detailJurnal->sum('kredit'), 0, ',', '.'
                        )
                    )
                    ->color('danger'),
            ])
            ->actions([
                // ✅ ViewAction otomatis jadi modal karena route 'view' dihapus dari getPages()
                Tables\Actions\ViewAction::make()
                    ->modalWidth('4xl'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurnalUmums::route('/'),
            // ✅ Route 'view' dihapus → ViewAction otomatis jadi modal
        ];
    }
}
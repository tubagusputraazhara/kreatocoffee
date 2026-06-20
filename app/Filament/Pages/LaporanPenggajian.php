<?php

namespace App\Filament\Pages;

use App\Models\Penggajian;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;

class LaporanPenggajian extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan Penggajian';

    protected static ?string $title = 'Laporan Penggajian';

    protected static string $view = 'filament.pages.laporan-penggajian';

    /**
     * Query dasar laporan: gabungan Penggajian + Karyawan + Jabatan (via relasi)
     */
    protected function getTableQuery(): Builder
    {
        return Penggajian::query()->with('karyawan');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('tanggal_bayar')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->placeholder('—')
                    ->sortable(),
                    
                TextColumn::make('karyawan.nama')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('karyawan.jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->formatStateUsing(fn (int $state): string => Penggajian::namaBulan()[$state] ?? $state)
                    ->sortable(),

                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),

                TextColumn::make('gaji_pokok')
                    ->label('Gaji Pokok')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tunjangan')
                    ->label('Tunjangan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('potongan')
                    ->label('Potongan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('gaji_bersih')
                    ->label('Gaji Bersih')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total')
                            ->money('IDR')
                    ),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Dibayar' => 'success',
                        'Ditolak' => 'danger',
                        default   => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('tanggal_bayar')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options(Penggajian::namaBulan()),

                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(
                        Penggajian::query()
                            ->distinct()
                            ->orderByDesc('tahun')
                            ->pluck('tahun', 'tahun')
                            ->toArray()
                    ),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Dibayar' => 'Dibayar',
                        'Ditolak' => 'Ditolak',
                    ]),

                SelectFilter::make('id_karyawan')
                    ->label('Karyawan')
                    ->relationship('karyawan', 'nama')
                    ->searchable(),
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $penggajians = $this->getTableQuery()->get();

                        $totalGajiBersih = $penggajians->sum('gaji_bersih');

                        $pdf = Pdf::loadView('pdf.laporan-penggajian', [
                            'penggajians' => $penggajians,
                            'totalGajiBersih' => $totalGajiBersih,
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'laporan-penggajian.pdf'
                        );
                    }),
            ])
            ->defaultSort('tahun', 'desc');
    }

    /**
     * Ringkasan untuk ditampilkan di atas tabel (summary card)
     */
    public function getTotalKaryawan(): int
    {
        return $this->getTableQuery()->distinct('id_karyawan')->count('id_karyawan');
    }

    public function getTotalGajiBersih(): string
    {
        $total = $this->getTableQuery()->sum('gaji_bersih');
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function getTotalSudahDibayar(): int
    {
        return $this->getTableQuery()->where('status', 'Dibayar')->count();
    }
}
<?php

namespace App\Filament\Resources\BiayaOperasionalResource\Widgets;

use App\Models\BiayaOperasional;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BiayaOperasionalStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBiaya = BiayaOperasional::sum('jumlah_biaya');

        $biayaBulanIni = BiayaOperasional::whereMonth('tgl_biaya', now()->month)
            ->whereYear('tgl_biaya', now()->year)
            ->sum('jumlah_biaya');

        $transaksiMingguIni = BiayaOperasional::whereBetween('tgl_biaya', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        $pengeluaranTerbesar = BiayaOperasional::max('jumlah_biaya') ?? 0;

        return [
            Stat::make('Total Biaya Operasional', 'Rp ' . number_format($totalBiaya, 0, ',', '.'))
                ->description('Total seluruh pengeluaran')
                ->color('danger'),

            Stat::make('Biaya Bulan Ini', 'Rp ' . number_format($biayaBulanIni, 0, ',', '.'))
                ->description('Akumulasi bulan berjalan')
                ->color('warning'),

            Stat::make('Transaksi Minggu Ini', $transaksiMingguIni)
                ->description('Jumlah transaksi biaya minggu ini')
                ->color('info'),

            Stat::make('Pengeluaran Terbesar', 'Rp ' . number_format($pengeluaranTerbesar, 0, ',', '.'))
                ->description('Nominal biaya tertinggi')
                ->color('success'),
        ];
    }
}
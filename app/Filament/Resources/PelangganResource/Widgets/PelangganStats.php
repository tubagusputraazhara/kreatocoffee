<?php

namespace App\Filament\Resources\PelangganResource\Widgets;

use App\Models\pelanggan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PelangganStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pelanggan', pelanggan::count())
                ->description('Semua pelanggan terdaftar')
                ->color('warning'),

            Stat::make(
                'Pelanggan Baru',
                pelanggan::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count()
            )
                ->description('Bulan ini')
                ->color('success'),

            Stat::make(
                'Pelanggan Minggu Ini',
                pelanggan::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ])->count()
            )
                ->description('Data pelanggan minggu ini')
                ->color('info'),

            Stat::make(
                'Pelanggan Hari Ini',
                pelanggan::whereDate('created_at', now()->toDateString())->count()
            )
                ->description('Data pelanggan hari ini')
                ->color('danger'),
        ];
    }
}
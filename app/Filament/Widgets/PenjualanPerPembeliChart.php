<?php

namespace App\Filament\Widgets;

use App\Models\Pemesanan;
use Filament\Widgets\ChartWidget;

class PenjualanPerPembeliChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'Total Penjualan Per Pembeli';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $penjualan = Pemesanan::query()
            ->selectRaw('nama_pemesan, SUM(total_harga) as total')
            ->groupBy('nama_pemesan')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $penjualan->pluck('total')->toArray(),

                    'backgroundColor' => [
                        '#f59e0b',
                        '#10b981',
                        '#3b82f6',
                        '#ef4444',
                        '#8b5cf6',
                        '#ec4899',
                        '#14b8a6',
                        '#f97316',
                        '#6366f1',
                        '#84cc16',
                        '#06b6d4',
                        '#a855f7',
                        '#22c55e',
                        '#eab308',
                        '#dc2626',
                        '#0ea5e9',
                        '#7c3aed',
                        '#16a34a',
                        '#f43f5e',
                        '#0891b2',
                    ],

                    'borderWidth' => 1,
                ],
            ],

            'labels' => $penjualan->pluck('nama_pemesan')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
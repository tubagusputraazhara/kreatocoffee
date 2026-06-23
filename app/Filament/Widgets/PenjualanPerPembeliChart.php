<?php

namespace App\Filament\Widgets;

use App\Models\Pemesanan;
use Filament\Widgets\ChartWidget;

class PenjualanPerPembeliChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan Per Pembeli';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

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
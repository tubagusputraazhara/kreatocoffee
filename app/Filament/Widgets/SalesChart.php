<?php

namespace App\Filament\Widgets;

use App\Models\Pemesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Per Bulan';

    protected function getData(): array
    {
        $sales = Pemesanan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('status', 'lunas')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $sales[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0b',
                ],
            ],

            'labels' => [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
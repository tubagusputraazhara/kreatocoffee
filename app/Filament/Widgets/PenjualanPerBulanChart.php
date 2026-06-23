<?php

namespace App\Filament\Widgets;

use App\Models\Pemesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PenjualanPerBulanChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Per Bulan';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $years = [];
        $currentYear = Carbon::now()->year;

        for ($y = $currentYear; $y >= $currentYear - 4; $y--) {
            $years[(string) $y] = (string) $y;
        }

        return $years;
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter ?? Carbon::now()->year;

        $labels = [
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
        ];

        $data = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {

            $total = Pemesanan::whereYear('created_at', $activeFilter)
                ->whereMonth('created_at', $bulan)
                ->sum('total_harga');

            $data[] = (float) $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245,158,11,0.2)',
                    'fill' => true,
                ],
            ],

            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
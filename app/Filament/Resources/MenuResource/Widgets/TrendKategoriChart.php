<?php

namespace App\Filament\Resources\MenuResource\Widgets;

use App\Models\MarketTrend;
use Filament\Widgets\ChartWidget;

class TrendKategoriChart extends ChartWidget
{
    protected int|string|array $columnSpan = 1;

    public function getHeading(): string
    {
        return "📊 Prediksi Kategori Terlaris " . now()->year;
    }

    protected function getData(): array
    {
        $latest = MarketTrend::latest()->first();

        $items = preg_split(
            '/\s*,\s*/',
            strtolower($latest?->kategori_terlaris ?? ''),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $labels = array_map(
            fn($item) => ucwords(trim($item)),
            $items
        );

        return [
            'datasets' => [[
                'data' => [45,30,15,10],

                'backgroundColor' => [
                    '#3B82F6',
                    '#22C55E',
                    '#F59E0B',
                    '#EC4899',
                ],

                'borderWidth' => 0,
            ]],

            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '70%',

            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                    ]
                ]
            ]
        ];
    }
}
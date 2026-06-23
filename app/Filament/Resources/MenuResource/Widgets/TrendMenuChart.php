<?php

namespace App\Filament\Resources\MenuResource\Widgets;

use App\Models\MarketTrend;
use Filament\Widgets\ChartWidget;

class TrendMenuChart extends ChartWidget
{
    protected static ?string $heading = '🔥 Top Menu/Rasa yang Sedang Tren';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $latest = MarketTrend::latest()->first();

        $items = preg_split(
            '/\s*,\s*/',
            strtolower($latest?->menu_populer ?? ''),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $labels = array_map(
            fn($item) => ucwords(trim($item)),
            $items
        );

        return [
            'datasets' => [[
                'label' => 'Popularitas',
                'data' => [90,80,70,60,50],
                'backgroundColor' => [
                    '#10B981',
                    '#06B6D4',
                    '#F59E0B',
                    '#8B5CF6',
                    '#EF4444'
                ],
                'borderRadius' => 15,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',

            'plugins' => [
                'legend' => [
                    'display' => false,
                ]
            ],

            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false
                    ]
                ],
                'y' => [
                    'grid' => [
                        'display' => false
                    ]
                ]
            ]
        ];
    }
}
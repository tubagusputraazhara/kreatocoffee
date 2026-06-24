<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardStats::class,
            \App\Filament\Widgets\PenjualanPerBulanChart::class,
            \App\Filament\Widgets\PenjualanPerPembeliChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }
}
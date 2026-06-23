<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\JurnalUmum;

class DetailJurnalUmumWidget extends Widget
{
    protected static string $view = 'filament.widgets.detail-jurnal-umum-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public $periode;

    public function mount(): void
    {
        $this->periode = now()->format('Y-m');
    }

    public function filterJurnal(): void
    {
        // re-render otomatis
    }

    public function getViewData(): array
    {
        $query = JurnalUmum::with('detailJurnal.coa');

        if ($this->periode) {
            [$year, $month] = explode('-', $this->periode);
            $query->whereYear('tanggal_jurnal', $year)
                  ->whereMonth('tanggal_jurnal', $month);
        }

        $jurnals = $query->orderBy('tanggal_jurnal')->get();

        return [
            'jurnals' => $jurnals,
            'periode' => $this->periode,
        ];
    }
}
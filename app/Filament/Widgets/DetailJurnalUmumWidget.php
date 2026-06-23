<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\JurnalUmum;

class DetailJurnalUmumWidget extends Widget
{
    protected static string $view = 'filament.widgets.detail-jurnal-umum-widget';

    protected static bool $isDiscovered = false;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public $periode;

    public function mount(): void
    {
        $this->periode = now()->format('Y-m');
    }

    public function filterJurnal(): void
    {
        //
    }

    public function getViewData(): array
    {
        $query = JurnalUmum::with('detailJurnal.coa');

        if ($this->periode) {
            [$year, $month] = explode('-', $this->periode);

            $query->whereYear('tanggal_jurnal', $year)
                ->whereMonth('tanggal_jurnal', $month);
        }

        return [
            'jurnals' => $query->orderBy('tanggal_jurnal')->get(),
            'periode' => $this->periode,
        ];
    }
}
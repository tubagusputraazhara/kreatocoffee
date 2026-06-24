<?php

namespace App\Filament\Resources\BukuBesarResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\JurnalUmum;
use App\Models\Coa;
use Carbon\Carbon;

class BukuBesar extends Widget
{
    protected static string $view = 'filament.resources.buku-besar-resource.widgets.buku-besar';

    protected int|string|array $columnSpan = 'full';

    public $periode_awal;
    public $periode_akhir;
    public $coa_id;

    public function mount(): void
    {
        $this->periode_awal = now()->format('Y-m');
        $this->periode_akhir = now()->format('Y-m');
        $this->coa_id = null;
    }

    public function filterJurnal(): void
    {
        // re-render otomatis
    }

    public function getViewData(): array
    {
        $saldoAwal = 0;

        $jurnalsQuery = JurnalUmum::with(['detailJurnal' => function ($query) {
            if ($this->coa_id) {
                $query->where('id_coa', $this->coa_id);
            }
            $query->with('coa');
        }])->orderBy('tanggal_jurnal')->orderBy('id_jurnal');

        if ($this->periode_awal && $this->periode_akhir) {
            $awal = Carbon::createFromFormat('Y-m', $this->periode_awal)->startOfMonth();
            $akhir = Carbon::createFromFormat('Y-m', $this->periode_akhir)->endOfMonth();

            // Hitung saldo awal sebelum periode
            if ($this->coa_id) {
                $saldoAwal = JurnalUmum::where('tanggal_jurnal', '<', $awal)
                    ->with(['detailJurnal' => function ($q) {
                        $q->where('id_coa', $this->coa_id);
                    }])
                    ->get()
                    ->flatMap->detailJurnal
                    ->reduce(fn($carry, $d) => $carry + ($d->debit - $d->kredit), 0);
            }

            $jurnalsQuery->whereBetween('tanggal_jurnal', [$awal, $akhir]);
        }

        $jurnals = $jurnalsQuery->get()->filter(fn($j) => $j->detailJurnal->isNotEmpty());

        return [
            'jurnals'       => $jurnals,
            'saldoAwal'     => $saldoAwal,
            'periode_awal'  => $this->periode_awal,
            'periode_akhir' => $this->periode_akhir,
            'coa_id'        => $this->coa_id,
        ];
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\JurnalUmum;
use Barryvdh\DomPDF\Facade\Pdf;

class JurnalExportController extends Controller
{
    public function exportPdf()
    {
        $jurnals = JurnalUmum::with('detailJurnal')->orderBy('tanggal_jurnal')->get();

        $pdf = Pdf::loadView('pdf.jurnal-umum', compact('jurnals'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Jurnal-Umum-' . now()->format('Y-m-d') . '.pdf');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Barryvdh\DomPDF\Facade\Pdf;

class PemesananExportController extends Controller
{
    public function exportPdf()
    {
        $pemesanans = Pemesanan::with('details')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.pemesanan', compact('pemesanans'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Pemesanan-' . now()->format('Y-m-d') . '.pdf');
    }
}
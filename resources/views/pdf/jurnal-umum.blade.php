<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jurnal Umum</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #6b7280; font-size: 11px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #C0392B; color: white; padding: 8px; text-align: left; }
        td { padding: 7px 8px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #f0f0f0; }
        .footer { margin-top: 20px; font-size: 11px; color: #6b7280; text-align: right; }
    </style>
</head>
<body>
    <h2>Kreato Coffee</h2>
    <p class="subtitle">Laporan Jurnal Umum</p>

    <table>
        <thead>
            <tr>
                <th>Nomor Jurnal</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Ref</th>
                <th class="text-right">Total Debit</th>
                <th class="text-right">Total Kredit</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDebit = 0; $totalKredit = 0; @endphp
            @foreach($jurnals as $jurnal)
                @php
                    $debit   = $jurnal->detailJurnal->sum('debit');
                    $kredit  = $jurnal->detailJurnal->sum('kredit');
                    $totalDebit  += $debit;
                    $totalKredit += $kredit;
                @endphp
                <tr>
                    <td>{{ $jurnal->nomor_jurnal }}</td>
                    <td>{{ \Carbon\Carbon::parse($jurnal->tanggal_jurnal)->format('d-m-Y') }}</td>
                    <td>{{ $jurnal->keterangan }}</td>
                    <td>{{ $jurnal->ref ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($debit, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($kredit, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Total</td>
                <td class="text-right">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</div>
</body>
</html>
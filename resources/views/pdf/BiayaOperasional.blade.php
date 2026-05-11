<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Biaya Operasional</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #4f46e5; color: white; padding: 8px; text-align: left; }
        td { padding: 7px 8px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .footer { margin-top: 20px; font-size: 11px; color: #6b7280; text-align: right; }
    </style>
</head>
<body>
    <h2>Daftar Biaya Operasional</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan Biaya</th>
                <th>PIC/Karyawan</th>
                <th>Nominal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($biayaOperasionals as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->tgl_biaya)->format('d-m-Y') }}</td>
                <td>{{ $item->nama_biaya }}</td>
                <td>{{ $item->karyawan->nama_karyawan ?? '-' }}</td>
                <td>Rp {{ number_format($item->jumlah_biaya, 0, ',', '.') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</div>
</body>
</html>
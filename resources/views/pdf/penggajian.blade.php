<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penggajian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        h2 { text-align: center; margin-bottom: 4px; }
        p.subtitle { text-align: center; color: #555; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background-color: #4f46e5; color: white; padding: 7px 6px; text-align: left; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .badge { padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: bold; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-dibayar  { background: #d1fae5; color: #065f46; }
        .badge-ditolak  { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 16px; font-size: 10px; color: #6b7280; text-align: right; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Penggajian</h2>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Karyawan</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th class="text-right">Gaji Pokok</th>
                <th class="text-right">Tunjangan</th>
                <th class="text-right">Potongan</th>
                <th class="text-right">Gaji Bersih</th>
                <th>Status</th>
                <th>Tgl Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penggajians as $item)
            @php
                $bulanList = \App\Models\Penggajian::namaBulan();
                $badgeClass = match($item->status) {
                    'Dibayar' => 'badge-dibayar',
                    'Ditolak' => 'badge-ditolak',
                    default   => 'badge-pending',
                };
            @endphp
            <tr>
                <td>{{ $item->id_penggajian }}</td>
                <td>{{ $item->karyawan->nama ?? '-' }}</td>
                <td>{{ $bulanList[$item->bulan] ?? $item->bulan }}</td>
                <td>{{ $item->tahun }}</td>
                <td class="text-right">Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->tunjangan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->potongan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->gaji_bersih, 0, ',', '.') }}</td>
                <td><span class="badge {{ $badgeClass }}">{{ $item->status }}</span></td>
                <td>{{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d-m-Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Total data: {{ count($penggajians) }} record</div>
</body>
</html>
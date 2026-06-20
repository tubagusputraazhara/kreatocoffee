<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penggajian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #999; padding: 6px 8px; }
        th { background-color: #f0f0f0; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        tfoot td { font-weight: bold; background-color: #f7f7f7; }
    </style>
</head>
<body>
    <h2>Laporan Penggajian</h2>
    <p class="text-center">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                 <th>Tanggal</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th class="text-right">Gaji Pokok</th>
                <th class="text-right">Tunjangan</th>
                <th class="text-right">Potongan</th>
                <th class="text-right">Gaji Bersih</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penggajians as $i => $p)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $p->tanggal ? \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') : '-' }}</td>
                    <td>{{ $p->karyawan->nama ?? '-' }}</td>
                    <td>{{ $p->karyawan->jabatan ?? '-' }}</td>
                    <td>{{ \App\Models\Penggajian::namaBulan()[$p->bulan] ?? $p->bulan }}</td>
                    <td>{{ $p->tahun }}</td>
                    <td class="text-right">{{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($p->tunjangan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($p->potongan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($p->gaji_bersih, 0, ',', '.') }}</td>
                    <td>{{ $p->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right">Total Gaji Bersih</td>
                <td class="text-right">Rp {{ number_format($totalGajiBersih, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
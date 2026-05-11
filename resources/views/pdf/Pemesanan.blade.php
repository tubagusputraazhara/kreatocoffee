<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        table.main { width: 100%; border-collapse: collapse; }
        table.main th { background-color: #4f46e5; color: white; padding: 8px; text-align: left; }
        table.main td { padding: 8px; border-bottom: 1px solid #eee; }
        .footer { margin-top: 30px; text-align: center; font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">LAPORAN TRANSAKSI PEMESANAN</h2>
        <span>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <table class="main">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesanans as $p) {{-- Menggunakan variabel $pemesanans sesuai kode di ListPemesanans.php --}}
            <tr>
                <td>{{ $p->id_pemesanan }}</td>
                <td>{{ $p->created_at->format('d/m/y') }}</td>
                <td>{{ $p->nama_pelanggan }}</td>
                <td>{{ $p->nama_pesanan }}</td>
                <td style="text-align: center;">{{ $p->jumlah }}</td>
                <td style="text-align: right;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Kreato Coffee - Dokumen Laporan Otomatis
    </div>
</body>
</html>
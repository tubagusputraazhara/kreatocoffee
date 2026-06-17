<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pelanggan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #B91C1C; color: white; padding: 8px; text-align: left; }
        td { padding: 7px 8px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .footer { margin-top: 20px; font-size: 11px; color: #6b7280; text-align: right; }
    </style>
</head>
<body>
    <h2>Daftar Pelanggan</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pelanggan</th>
                <th>No HP</th>
                <th>Status Aktif</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggans as $pelanggan)
            <tr>
                <td>{{ $pelanggan->id }}</td>
                <td>{{ $pelanggan->nama_pelanggan }}</td>
                <td>{{ $pelanggan->no_hp }}</td>
                <td>{{ $pelanggan->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td>{{ \Carbon\Carbon::parse($pelanggan->created_at)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</div>
</body>
</html>//
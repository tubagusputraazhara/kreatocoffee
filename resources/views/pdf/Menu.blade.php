<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Menu</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #4f46e5; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .footer { margin-top: 20px; font-size: 11px; color: #6b7280; text-align: right; }
    </style>
</head>
<body>
    <h2>Daftar Menu Restoran</h2>
    <p class="subtitle">Katalog Master Data Menu</p>
    <table>
        <thead>
            <tr>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
            <tr>
                <td>{{ $menu->nama_menu }}</td>
                <td>{{ $menu->kategori }}</td>
                <td>{{ $menu->stok }}</td>
                <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</div>
</body>
</html>
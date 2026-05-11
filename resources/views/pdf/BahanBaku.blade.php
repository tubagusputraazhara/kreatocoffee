<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Bahan Baku</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th {
            background-color: #4f46e5;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 7px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:nth-child(even) {
            background-color: #f3f4f6;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>

<body>

    <h2>Data Bahan Baku</h2>

    <table>
        <thead>
            <tr>
                <th>ID Bahan</th>
                <th>Nama Bahan</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Harga</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($bahanBakus as $bahan)
            <tr>
                <td>{{ $bahan->id }}</td>
                <td>{{ $bahan->nama_bahan }}</td>
                <td>{{ $bahan->kategori }}</td>
                <td>{{ $bahan->stok }}</td>
                <td>{{ $bahan->satuan }}</td>
                <td>Rp {{ number_format($bahan->harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>

//
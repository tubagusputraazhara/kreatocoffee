<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Karyawan</title>
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
    <h2>Daftar Karyawan</h2>
    <table>
        <thead>
            <tr>
                <th>ID Karyawan</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($karyawans as $karyawan)
            <tr>
                <td>{{ $karyawan->id_karyawan }}</td>
                <td>{{ $karyawan->nama }}</td>
                <td>{{ $karyawan->jenis_kelamin }}</td>
                <td>{{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d-m-Y') }}</td>
                <td>Rp {{ number_format($karyawan->gaji, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan — Kreato Coffee</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #2C1A0E;
            background: #fff;
        }

        .letterhead {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px 24px 16px;
            border-bottom: 3px solid #C0392B;
            margin-bottom: 20px;
        }

        .brand-name {
            font-size: 22px;
            font-weight: bold;
            color: #C0392B;
            letter-spacing: 1px;
        }

        .brand-sub {
            font-size: 10px;
            color: #9E8E84;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .doc-info {
            text-align: right;
        }

        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #2C1A0E;
        }

        .doc-date {
            font-size: 10px;
            color: #9E8E84;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 24px;
        }

        thead th {
            background-color: #C0392B;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }

        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #F5EDE8;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background-color: #FDF8F5;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-pending  { background: #FEF3C7; color: #92400E; }
        .badge-selesai  { background: #D1FAE5; color: #065F46; }
        .badge-batal    { background: #FEE2E2; color: #991B1B; }
        .badge-customer { background: #EDE9FE; color: #4C1D95; }
        .badge-kasir    { background: #DBEAFE; color: #1E40AF; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .summary-box {
            background: #FDF8F5;
            border: 1px solid #EDE0D8;
            border-radius: 8px;
            padding: 12px 16px;
            width: 240px;
            float: right;
            margin-bottom: 40px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid #EDE0D8;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 12px;
            padding-top: 8px;
        }

        .summary-label { color: #9E8E84; }
        .summary-val   { color: #2C1A0E; font-weight: 500; }
        .summary-total { color: #C0392B; }

        /* TTD Section */
        .ttd-section {
            clear: both;
            margin-top: 20px;
            display: table;
            width: 100%;
        }

        .ttd-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 0 12px;
        }

        .ttd-title {
            font-size: 11px;
            color: #5C4033;
            margin-bottom: 60px;
        }

        .ttd-line {
            border-top: 1px solid #2C1A0E;
            padding-top: 6px;
        }

        .ttd-name {
            font-size: 11px;
            font-weight: bold;
            color: #2C1A0E;
        }

        .ttd-jabatan {
            font-size: 10px;
            color: #9E8E84;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 10px;
            color: #B0A09A;
            border-top: 1px solid #EDE0D8;
            padding-top: 10px;
        }

        .clearfix::after {
            content: '';
            display: table;
            clear: both;
        }
    </style>
</head>
<body>

    {{-- Letterhead --}}
    <div class="letterhead">
        <div>
            <div class="brand-name">Kreato Coffee</div>
            <div class="brand-sub">Coffee &amp; Eatery</div>
        </div>
        <div class="doc-info">
            <div class="doc-title">Laporan Transaksi Pemesanan</div>
            <div class="doc-date">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Pemesan</th>
                <th>Meja</th>
                <th>Sumber</th>
                <th>Menu Dipesan</th>
                <th>Status</th>
                <th class="text-right">Total</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($pemesanans as $p)
                @php $grandTotal += $p->total_harga; @endphp
                <tr>
                    <td>{{ $p->kode_pemesanan }}</td>
                    <td>{{ $p->nama_pemesan }}</td>
                    <td>{{ $p->no_meja }}</td>
                    <td>
                        <span class="badge badge-{{ $p->sumber }}">
                            {{ ucfirst($p->sumber) }}
                        </span>
                    </td>
                    <td>
                        @foreach($p->details as $d)
                            {{ $d->nama_menu }} (x{{ $d->qty }})<br>
                        @endforeach
                    </td>
                    <td>
                        <span class="badge badge-{{ $p->status }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                    </td>
                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary --}}
    <div class="clearfix">
        <div class="summary-box">
            <div class="summary-row">
                <span class="summary-label">Total Transaksi</span>
                <span class="summary-val">{{ $pemesanans->count() }} pesanan</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Selesai</span>
                <span class="summary-val">
                    {{ $pemesanans->where('status', 'selesai')->count() }}
                </span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Pending</span>
                <span class="summary-val">
                    {{ $pemesanans->where('status', 'pending')->count() }}
                </span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Grand Total</span>
                <span class="summary-total">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- TTD Section --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <p class="ttd-title">Dibuat Oleh</p>
            <div class="ttd-line">
                <p class="ttd-name">( _________________ )</p>
                <p class="ttd-jabatan">Staff / Kasir</p>
            </div>
        </div>
        <div class="ttd-box">
            <p class="ttd-title">Diperiksa Oleh</p>
            <div class="ttd-line">
                <p class="ttd-name">( _________________ )</p>
                <p class="ttd-jabatan">Manajer Operasional</p>
            </div>
        </div>
        <div class="ttd-box">
            <p class="ttd-title">Disetujui Oleh</p>
            <div class="ttd-line">
                <p class="ttd-name">( _________________ )</p>
                <p class="ttd-jabatan">CEO Kreato Coffee</p>
            </div>
        </div>
    </div>

    <div class="footer">
        Kreato Coffee — Dokumen ini dicetak secara otomatis oleh sistem
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Checkout — Kreato Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #EDE7E3;
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }

        .app-container {
            width: 100%;
            max-width: 430px;
            min-height: 100vh;
            background: #F5F0EC;
            position: relative;
            box-shadow: 0 0 30px rgba(0,0,0,0.08);
        }

        .topbar {
            background: #C0392B;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .back-btn {
            background: rgba(255,255,255,0.18);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
        }

        .topbar-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.75);
            margin-top: 2px;
        }

        .content-wrap {
            padding: 16px 14px 140px;
        }

        /* Info Pemesan */
        .info-card {
            background: #fff;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .info-card-title {
            font-size: 12px;
            font-weight: 600;
            color: #5C4033;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #F5EDE8;
        }

        .info-row:last-child { border-bottom: none; }

        .info-label {
            font-size: 12px;
            color: #9E8E84;
        }

        .info-val {
            font-size: 13px;
            font-weight: 500;
            color: #2C1A0E;
        }

        /* Order Items */
        .section-title {
            font-size: 12px;
            font-weight: 600;
            color: #5C4033;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .order-card {
            background: #fff;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .order-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #F5EDE8;
            gap: 8px;
        }

        .order-item:last-child { border-bottom: none; }

        .order-item-name {
            font-size: 13px;
            font-weight: 500;
            color: #2C1A0E;
        }

        .order-item-price {
            font-size: 11px;
            color: #9E8E84;
            margin-top: 2px;
        }

        .order-item-qty {
            background: #F5EDE8;
            color: #C0392B;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .order-item-subtotal {
            font-size: 13px;
            font-weight: 600;
            color: #C0392B;
            min-width: 80px;
            text-align: right;
            flex-shrink: 0;
        }

        /* Ringkasan Total */
        .summary-card {
            background: #fff;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #F5EDE8;
        }

        .summary-row:last-child {
            border-bottom: none;
            padding-top: 12px;
            margin-top: 4px;
        }

        .summary-label {
            font-size: 13px;
            color: #9E8E84;
        }

        .summary-val {
            font-size: 13px;
            font-weight: 500;
            color: #2C1A0E;
        }

        .summary-total-label {
            font-size: 15px;
            font-weight: 600;
            color: #2C1A0E;
        }

        .summary-total-val {
            font-size: 15px;
            font-weight: 600;
            color: #C0392B;
        }

        /* Catatan */
        .catatan-card {
            background: #fff;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .catatan-card textarea {
            width: 100%;
            border: 1.5px solid #EDE0D8;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            color: #2C1A0E;
            background: #FDFAF8;
            resize: none;
            outline: none;
        }

        .catatan-card textarea:focus {
            border-color: #C0392B;
        }

        /* Bottom bar */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 430px;
            background: #fff;
            padding: 14px 16px calc(14px + env(safe-area-inset-bottom));
            box-shadow: 0 -6px 18px rgba(0,0,0,0.08);
            border-radius: 18px 18px 0 0;
        }

        .total-row-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .total-bar-label {
            font-size: 13px;
            color: #9E8E84;
        }

        .total-bar-val {
            font-size: 18px;
            font-weight: 600;
            color: #C0392B;
        }

        .btn-pay {
            width: 100%;
            padding: 14px;
            background: #C0392B;
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-pay:hover { background: #A93226; }
        .btn-pay:active { transform: scale(0.98); }

        @media (min-width: 768px) {
            body { padding: 20px 0; }
            .app-container { border-radius: 28px; min-height: 95vh; }
        }
    </style>
</head>
<body>

<div class="app-container">

    {{-- Topbar --}}
    <div class="topbar">
        <button class="back-btn" onclick="history.back()">←</button>
        <div>
            <p class="topbar-title">Konfirmasi Pesanan</p>
            <p class="topbar-sub">{{ session('no_meja') }} — {{ session('nama_pemesan') }}</p>
        </div>
    </div>

    <div class="content-wrap">

        {{-- Info Pemesan --}}
        <div class="info-card">
            <p class="info-card-title">Info Pemesan</p>
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-val">{{ session('nama_pemesan') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Meja</span>
                <span class="info-val">{{ session('no_meja') }}</span>
            </div>
            @if(session('no_wa'))
            <div class="info-row">
                <span class="info-label">WhatsApp</span>
                <span class="info-val">{{ session('no_wa') }}</span>
            </div>
            @endif
            @if(session('email'))
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-val">{{ session('email') }}</span>
            </div>
            @endif
        </div>

        {{-- Daftar Pesanan --}}
        <p class="section-title">Pesanan Kamu</p>
        <div class="order-card">
            @foreach($cart as $item)
            <div class="order-item">
                <div style="flex:1">
                    <p class="order-item-name">{{ $item['nama_menu'] }}</p>
                    <p class="order-item-price">
                        Rp {{ number_format($item['harga'], 0, ',', '.') }}
                    </p>
                </div>
                <span class="order-item-qty">x{{ $item['qty'] }}</span>
                <span class="order-item-subtotal">
                    Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                </span>
            </div>
            @endforeach
        </div>

        {{-- Catatan --}}
        <div class="catatan-card">
            <p class="info-card-title">Catatan (opsional)</p>
            <textarea
                id="catatan"
                rows="3"
                placeholder="Contoh: Kurang manis, tanpa es, dll...">
            </textarea>
        </div>

        {{-- Ringkasan Total --}}
        <div class="summary-card">
            <p class="info-card-title">Ringkasan</p>
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-val">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Biaya Layanan</span>
                <span class="summary-val">Rp 0</span>
            </div>
            <div class="summary-row">
                <span class="summary-total-label">Total</span>
                <span class="summary-total-val">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>
        </div>

    </div>

    {{-- Bottom Bar --}}
    <div class="bottom-bar">
        <div class="total-row-bar">
            <span class="total-bar-label">Total Pembayaran</span>
            <span class="total-bar-val">
                Rp {{ number_format($total, 0, ',', '.') }}
            </span>
        </div>
        <form action="{{ route('order.payment') }}" method="POST">
            @csrf
            <input type="hidden" name="catatan" id="catatan-input">
            <button type="submit" class="btn-pay"
                onclick="document.getElementById('catatan-input').value = document.getElementById('catatan').value">
                Bayar Sekarang →
            </button>
        </form>
    </div>

</div>

</body>
</html>
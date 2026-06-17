<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Pembayaran — Kreato Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 30px rgba(0,0,0,0.08);
        }

        .topbar {
            background: #C0392B;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
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

        .topbar-title { font-size: 16px; font-weight: 600; color: #fff; }
        .topbar-sub { font-size: 11px; color: rgba(255,255,255,0.75); margin-top: 2px; }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            text-align: center;
        }

        .coffee-icon {
            width: 88px;
            height: 88px;
            background: #C0392B;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 24px;
        }

        .title { font-size: 20px; font-weight: 600; color: #2C1A0E; margin-bottom: 6px; }
        .subtitle { font-size: 13px; color: #9E8E84; margin-bottom: 6px; }
        .order-id { font-size: 11px; color: #C4B0A8; font-family: monospace; margin-bottom: 28px; }

        .total-box {
            background: #fff;
            border-radius: 20px;
            padding: 20px 28px;
            margin-bottom: 28px;
            width: 100%;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .total-label { font-size: 12px; color: #9E8E84; margin-bottom: 6px; }
        .total-val { font-size: 26px; font-weight: 600; color: #C0392B; }

        .info-box {
            background: #FFF8F7;
            border: 1.5px solid #F5C6C0;
            border-radius: 14px;
            padding: 14px 16px;
            width: 100%;
            margin-bottom: 24px;
            text-align: left;
        }

        .info-box-title { font-size: 12px; font-weight: 600; color: #C0392B; margin-bottom: 8px; }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 12px;
        }

        .info-row-label { color: #9E8E84; }
        .info-row-val { color: #2C1A0E; font-weight: 500; }

        .btn-pay {
            width: 100%;
            padding: 16px;
            background: #C0392B;
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-bottom: 12px;
        }

        .btn-pay:hover { background: #A93226; }
        .btn-pay:active { transform: scale(0.98); }
        .btn-pay:disabled { background: #E0C8C5; cursor: not-allowed; }

        .btn-back {
            background: none;
            border: none;
            font-size: 13px;
            color: #9E8E84;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            padding: 8px;
        }

        .loading-wrap {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
        }

        .loading-wrap.show { display: flex; }

        .spinner {
            width: 24px;
            height: 24px;
            border: 3px solid #EDE0D8;
            border-top-color: #C0392B;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .loading-text { font-size: 12px; color: #9E8E84; }

        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 768px) {
            body { background: #F5F0EC; }
            .app-container { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <div class="topbar">
        <button class="back-btn" onclick="history.back()">←</button>
        <div>
            <p class="topbar-title">Pembayaran</p>
            <p class="topbar-sub">{{ session('no_meja') }} — {{ session('nama_pemesan') }}</p>
        </div>
    </div>

    <div class="content">
        <div class="coffee-icon">☕</div>

        <p class="title">Selesaikan Pembayaran</p>
        <p class="subtitle">Pesanan kamu sudah siap diproses</p>
        <p class="order-id">{{ $kodePemesanan }}</p>

        <div class="total-box">
            <p class="total-label">Total Pembayaran</p>
            <p class="total-val">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>

        <div class="info-box">
            <p class="info-box-title">Info Pesanan</p>
            <div class="info-row">
                <span class="info-row-label">Nama</span>
                <span class="info-row-val">{{ session('nama_pemesan') }}</span>
            </div>
            <div class="info-row">
                <span class="info-row-label">Meja</span>
                <span class="info-row-val">{{ session('no_meja') }}</span>
            </div>
            @if(session('no_wa'))
            <div class="info-row">
                <span class="info-row-label">WhatsApp</span>
                <span class="info-row-val">{{ session('no_wa') }}</span>
            </div>
            @endif
        </div>

        <button class="btn-pay" id="pay-btn" onclick="bayar()">
            Bayar Sekarang →
        </button>

        <button class="btn-back" onclick="history.back()">
            ← Kembali ke checkout
        </button>

        <div class="loading-wrap" id="loading-wrap">
            <div class="spinner"></div>
            <p class="loading-text">Membuka halaman pembayaran...</p>
        </div>
    </div>

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    function bayar() {
        const btn = document.getElementById('pay-btn');
        const loading = document.getElementById('loading-wrap');

        btn.disabled = true;
        btn.textContent = 'Memproses...';
        loading.classList.add('show');

        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route("order.success") }}';
            },
            onPending: function(result) {
                window.location.href = '{{ route("order.success") }}';
            },
            onError: function(result) {
                alert('Pembayaran gagal. Silakan coba lagi.');
                btn.disabled = false;
                btn.textContent = 'Bayar Sekarang →';
                loading.classList.remove('show');
            },
            onClose: function() {
                btn.disabled = false;
                btn.textContent = 'Bayar Sekarang →';
                loading.classList.remove('show');
            }
        });
    }
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Pesanan Berhasil — Kreato Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            text-align: center;
            box-shadow: 0 0 30px rgba(0,0,0,0.08);
        }

        .success-circle {
            width: 100px;
            height: 100px;
            background: #27AE60;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin: 0 auto 28px;
            animation: popIn 0.4s ease;
        }

        @keyframes popIn {
            from { transform: scale(0.5); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        .success-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #2C1A0E;
            margin-bottom: 8px;
        }

        .success-sub {
            font-size: 13px;
            color: #9E8E84;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .info-card {
            background: #fff;
            border-radius: 20px;
            padding: 20px;
            width: 100%;
            margin-bottom: 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .info-card-title {
            font-size: 12px;
            font-weight: 600;
            color: #5C4033;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #F5EDE8;
            font-size: 13px;
        }

        .info-row:last-child { border-bottom: none; }
        .info-label { color: #9E8E84; }
        .info-val { color: #2C1A0E; font-weight: 500; }

        .waiting-box {
            background: #FFF8E7;
            border: 1.5px solid #F5D98A;
            border-radius: 14px;
            padding: 14px 16px;
            width: 100%;
            margin-bottom: 28px;
            font-size: 12px;
            color: #8B6914;
            line-height: 1.6;
        }

        .btn-order-again {
            width: 100%;
            padding: 15px;
            background: #C0392B;
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: block;
        }

        .btn-order-again:hover { background: #A93226; }

        .footer {
            margin-top: 24px;
            font-size: 11px;
            color: #C4B0A8;
        }

        @media (max-width: 768px) {
            body { background: #F5F0EC; }
            .app-container { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <div class="success-circle">✓</div>

    <h1 class="success-title">Pesanan Masuk!</h1>
    <p class="success-sub">
        Terima kasih! Pesananmu sedang diproses<br>
        oleh tim Kreato Coffee.
    </p>

    <div class="waiting-box">
        ☕ Santai dulu, pesananmu sedang kami siapkan. Estimasi waktu penyajian 10–15 menit.
    </div>

    <a href="{{ route('order.index') }}" class="btn-order-again">
        Pesan Lagi →
    </a>

    <p class="footer">© {{ date('Y') }} Kreato Coffee. All rights reserved.</p>

</div>

</body>
</html>
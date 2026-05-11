<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kreato Coffee — Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FAF9F6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Header */
        .header {
            width: 100%;
            background-color: #C0392B;
            padding: 32px 24px 48px;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -24px;
            left: 0;
            right: 0;
            height: 48px;
            background-color: #FAF9F6;
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }

        .logo-wrap {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg { width: 36px; height: 36px; fill: #fff; }

        .logo-name {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #fff;
            letter-spacing: 1px;
        }

        .logo-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.75);
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 300;
        }

        /* Card form */
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 28px 24px;
            width: 90%;
            max-width: 420px;
            margin-top: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #2C1A0E;
            margin-bottom: 4px;
        }

        .card-subtitle {
            font-size: 12px;
            color: #9E8E84;
            margin-bottom: 24px;
        }

        /* Input */
        .field { margin-bottom: 18px; }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #5C4033;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .field .badge-optional {
            font-size: 10px;
            color: #B0A09A;
            font-weight: 400;
            margin-left: 4px;
        }

        .field input,
        .field select {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #EDE0D8;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #2C1A0E;
            background: #FDFAF8;
            outline: none;
            transition: border-color 0.2s;
        }

        .field input::placeholder { color: #C4B0A8; }
        .field input:focus,
        .field select:focus { border-color: #C0392B; background: #fff; }

        .field select { appearance: none; cursor: pointer; }
        .select-wrap { position: relative; }

        .select-wrap::after {
            content: '▾';
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9E8E84;
            pointer-events: none;
            font-size: 14px;
        }

        /* Divider opsional */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }

        .divider span {
            font-size: 11px;
            color: #B0A09A;
            white-space: nowrap;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #EDE0D8;
        }

        /* Button */
        .btn-next {
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
            margin-top: 8px;
            letter-spacing: 0.5px;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-next:hover { background: #A93226; }
        .btn-next:active { transform: scale(0.98); }

        .btn-next .arrow { margin-left: 8px; }

        /* Footer */
        .footer {
            margin: 28px 0 20px;
            text-align: center;
            font-size: 11px;
            color: #C4B0A8;
        }

        /* Error */
        .error-msg {
            font-size: 11px;
            color: #C0392B;
            margin-top: 4px;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="logo-wrap">
            <div class="logo-icon">
                {{-- Icon kopi --}}
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 24h32l-4 20H16L12 24z"/>
                    <path d="M44 28h6a6 6 0 0 1 0 12h-6"/>
                    <path d="M22 16 Q24 10 26 16 Q28 10 30 16" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            <span class="logo-name">Kreato Coffee</span>
            <span class="logo-sub">Coffee &amp; Eatery</span>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <p class="card-title">Selamat datang! ☕</p>
        <p class="card-subtitle">Isi informasi berikut sebelum memesan</p>

        <form action="{{ route('order.storeInfo') }}" method="POST">
            @csrf

            {{-- Nama Pemesan --}}
            <div class="field">
                <label for="nama_pemesan">Nama Pemesan</label>
                <input
                    type="text"
                    id="nama_pemesan"
                    name="nama_pemesan"
                    placeholder="Contoh: Budi"
                    value="{{ old('nama_pemesan') }}"
                    required
                >
                @error('nama_pemesan')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilih Meja --}}
            <div class="field">
                <label for="no_meja">Pilih Meja</label>
                <div class="select-wrap">
                    <select id="no_meja" name="no_meja" required>
                        <option value="" disabled selected>-- Pilih meja --</option>
                        @foreach($daftarMeja as $meja)
                            <option value="{{ $meja }}" {{ old('no_meja') == $meja ? 'selected' : '' }}>
                                {{ $meja }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('no_meja')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="divider"><span>Opsional</span></div>

            {{-- No WhatsApp --}}
            <div class="field">
                <label for="no_wa">
                    Nomor WhatsApp
                    <span class="badge-optional">(untuk invoice)</span>
                </label>
                <input
                    type="tel"
                    id="no_wa"
                    name="no_wa"
                    placeholder="Contoh: 08123456789"
                    value="{{ old('no_wa') }}"
                >
                @error('no_wa')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="field">
                <label for="email">
                    Email
                    <span class="badge-optional">(untuk faktur)</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Contoh: budi@email.com"
                    value="{{ old('email') }}"
                >
                @error('email')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-next">
                Lihat Menu <span class="arrow">→</span>
            </button>
        </form>
    </div>

    <p class="footer">© {{ date('Y') }} Kreato Coffee. All rights reserved.</p>

</body>
</html>
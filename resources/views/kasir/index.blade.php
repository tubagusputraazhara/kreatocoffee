<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir — Kreato Coffee</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #F5F0EC;
            height: 100vh;
            overflow: hidden;
        }

        .wrapper {
            display: flex;
            height: 100vh;
        }

        /* =====================
            LEFT SIDE
        ===================== */
        .left {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .brand { display: flex; align-items: center; gap: 12px; }

        .brand-dot {
            width: 10px; height: 10px;
            background: #C0392B;
            border-radius: 50%;
        }

        .title { font-size: 22px; font-weight: 700; color: #2C1A0E; }
        .subtitle { font-size: 12px; color: #9E8E84; margin-top: 2px; }

        .search {
            width: 260px;
            padding: 11px 16px;
            border: none;
            border-radius: 14px;
            background: #fff;
            font-family: 'Poppins';
            outline: none;
            font-size: 13px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Form Pemesanan */
        .order-form {
            background: #fff;
            padding: 20px 24px;
            border-radius: 18px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .form-title {
            font-size: 14px;
            font-weight: 600;
            color: #5C4033;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .form-group label {
            font-size: 11px;
            font-weight: 600;
            color: #9E8E84;
            letter-spacing: 0.3px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            margin-top: 6px;
            padding: 10px 12px;
            border: 1.5px solid #EDE0D8;
            border-radius: 10px;
            font-family: 'Poppins';
            font-size: 13px;
            outline: none;
            color: #2C1A0E;
            background: #FDFAF8;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus { border-color: #C0392B; }

        .form-group input[readonly] {
            background: #F5EDE8;
            color: #9E8E84;
            cursor: not-allowed;
        }

        /* Kategori */
        .kategori-wrap {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            margin-bottom: 20px;
        }

        .kategori-wrap::-webkit-scrollbar { display: none; }

        .kategori-btn {
            padding: 8px 18px;
            border: none;
            border-radius: 20px;
            background: #fff;
            cursor: pointer;
            white-space: nowrap;
            font-size: 13px;
            font-family: 'Poppins';
            color: #5C4033;
            transition: all 0.2s;
        }

        .kategori-btn.active {
            background: #C0392B;
            color: #fff;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .menu-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: transform 0.15s;
        }

        .menu-card:hover { transform: translateY(-2px); }

        .menu-img {
            height: 130px;
            background: #F0E3D5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        .menu-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-body { padding: 14px; }

        .menu-name {
            font-size: 14px;
            font-weight: 600;
            color: #2C1A0E;
            margin-bottom: 3px;
        }

        .menu-desc {
            font-size: 11px;
            color: #9E8E84;
            line-height: 1.5;
            margin-bottom: 8px;
            height: 32px;
            overflow: hidden;
        }

        .menu-price {
            color: #C0392B;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .btn-add {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: #C0392B;
            color: #fff;
            font-family: 'Poppins';
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-add:hover { background: #A93226; }

        /* =====================
            RIGHT SIDE CART
        ===================== */
        .right {
            width: 360px;
            background: #fff;
            border-left: 1px solid #EEE3DB;
            display: flex;
            flex-direction: column;
        }

        .cart-header {
            padding: 20px 24px;
            border-bottom: 1px solid #F0E3D5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-title { font-size: 18px; font-weight: 700; color: #2C1A0E; }

        .cart-count {
            background: #C0392B;
            color: #fff;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 600;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            gap: 8px;
        }

        .cart-name { font-size: 13px; font-weight: 600; color: #2C1A0E; }

        .cart-price-info { font-size: 11px; color: #9E8E84; margin-top: 2px; }

        .cart-subtotal { font-size: 13px; font-weight: 600; color: #C0392B; }

        .qty-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #F5F0EC;
            padding: 4px 8px;
            border-radius: 10px;
        }

        .qty-btn {
            width: 24px;
            height: 24px;
            border: none;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #C0392B;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .qty-val { font-size: 14px; font-weight: 600; min-width: 20px; text-align: center; }

        .empty {
            text-align: center;
            color: #B0A09A;
            margin-top: 60px;
            font-size: 13px;
            line-height: 2;
        }

        .cart-footer {
            padding: 20px 24px;
            border-top: 1px solid #F0E3D5;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }

        .summary-label { color: #9E8E84; }
        .summary-val { font-weight: 500; color: #2C1A0E; }

        .divider {
            border: none;
            border-top: 1px dashed #EDE0D8;
            margin: 10px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .total-label { font-size: 13px; color: #9E8E84; }

        .total-value {
            font-size: 22px;
            font-weight: 700;
            color: #2C1A0E;
        }

        .btn-pay {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 14px;
            background: #C0392B;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Poppins';
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-pay:hover { background: #A93226; }
        .btn-pay:disabled { background: #D5A49E; cursor: not-allowed; }

        /* Loading overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
        }

        .loading-overlay.show { display: flex; }

        .loading-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .loading-text { color: #fff; font-size: 14px; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #2C1A0E;
            color: #fff;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 13px;
            transition: transform 0.3s;
            z-index: 9998;
        }

        .toast.show { transform: translateX(-50%) translateY(0); }
        .toast.success { background: #065F46; }
        .toast.error { background: #991B1B; }
    </style>
</head>
<body>

{{-- Loading Overlay --}}
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
    <p class="loading-text">Memproses pembayaran...</p>
</div>

{{-- Toast --}}
<div class="toast" id="toast"></div>

<div class="wrapper">

    {{-- LEFT --}}
    <div class="left">

        {{-- Header --}}
        <div class="header">
            <div class="brand">
                <div class="brand-dot"></div>
                <div>
                    <div class="title">Kasir Kreato Coffee</div>
                    <div class="subtitle">Point of Sales System</div>
                </div>
            </div>
            <input type="text" class="search" id="search-input" placeholder="🔍 Cari menu...">
        </div>

        {{-- Form Data Pemesanan --}}
        <div class="order-form">
            <p class="form-title">Data Pemesanan</p>
            <div class="form-grid">
                <div class="form-group">
                    <label>Pelanggan</label>
                    <select id="select-pelanggan" onchange="updateNama()">
                        <option value="">Pilih Pelanggan</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}"         
                                data-nama="{{ $p->nama_pelanggan }}">
                                {{ $p->nama_pelanggan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Pemesan</label>
                    <input type="text" id="input-nama" placeholder="Otomatis terisi" readonly>
                </div>

                <div class="form-group">
                    <label>No Meja</label>
                    <select id="select-meja">
                        <option value="">Pilih Meja</option>
                        @for($i = 1; $i <= 30; $i++)
                            <option value="Meja {{ $i }}">Meja {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <input type="text" id="input-catatan" placeholder="Catatan tambahan...">
                </div>
            </div>
        </div>

        {{-- Kategori --}}
        <div class="kategori-wrap">
            <button class="kategori-btn active" onclick="filterKategori('semua', this)">
                Semua
            </button>
            @foreach($menus->keys() as $kategori)
                <button class="kategori-btn"
                    onclick="filterKategori('{{ Str::slug($kategori) }}', this)">
                    {{ $kategori }}
                </button>
            @endforeach
        </div>

        {{-- Menu Grid --}}
        <div class="menu-grid" id="menu-grid">
            @foreach($menus as $kategori => $items)
                @foreach($items as $menu)
                    <div class="menu-card"
                        data-kategori="{{ Str::slug($kategori) }}"
                        data-nama="{{ strtolower($menu->nama_menu) }}">
                        <div class="menu-img">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}"
                                    alt="{{ $menu->nama_menu }}">
                            @else
                                ☕
                            @endif
                        </div>
                        <div class="menu-body">
                            <div class="menu-name">{{ $menu->nama_menu }}</div>
                            <div class="menu-desc">{{ $menu->deskripsi }}</div>
                            <div class="menu-price">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </div>
                            <button class="btn-add"
                                onclick="addToCart(
                                    '{{ $menu->id_menu }}',
                                    '{{ addslashes($menu->nama_menu) }}',
                                    {{ $menu->harga }}
                                )">
                                + Tambah
                            </button>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

    </div>

    {{-- RIGHT CART --}}
    <div class="right">
        <div class="cart-header">
            <span class="cart-title">Keranjang</span>
            <span class="cart-count" id="cart-count">0 item</span>
        </div>

        <div class="cart-items" id="cart-items">
            <div class="empty">
                ☕<br>Belum ada pesanan<br>
                <span style="font-size:11px">Pilih menu di sebelah kiri</span>
            </div>
        </div>

        <div class="cart-footer">
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-val" id="subtotal">Rp 0</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Biaya Layanan</span>
                <span class="summary-val">Rp 0</span>
            </div>
            <hr class="divider">
            <div class="total-row">
                <span class="total-label">Total Bayar</span>
                <span class="total-value" id="total">Rp 0</span>
            </div>
            <button class="btn-pay" id="btn-pay" onclick="prosesBayar()">
                Bayar Sekarang →
            </button>
        </div>
    </div>

</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let keranjang   = [];

    // =====================
    // Update nama otomatis
    // =====================
    function updateNama() {
        const select = document.getElementById('select-pelanggan');
        const opt    = select.options[select.selectedIndex];
        document.getElementById('input-nama').value = opt.getAttribute('data-nama') || '';
    }

    // =====================
    // Tambah ke keranjang
    // =====================
    function addToCart(id, nama, harga) {
        const idx = keranjang.findIndex(i => i.id === id);

        if (idx > -1) {
            keranjang[idx].qty++;
        } else {
            keranjang.push({ id, nama, harga: parseInt(harga), qty: 1 });
        }

        renderKeranjang();
        showToast(nama + ' ditambahkan', 'success');
    }

    // =====================
    // Ubah qty
    // =====================
    function changeQty(id, delta) {
        const idx = keranjang.findIndex(i => i.id === id);

        if (idx > -1) {
            keranjang[idx].qty += delta;

            if (keranjang[idx].qty <= 0) {
                keranjang.splice(idx, 1);
            }
        }

        renderKeranjang();
    }

    // =====================
    // Render keranjang
    // =====================
    function renderKeranjang() {
        const container = document.getElementById('cart-items');
        const countEl   = document.getElementById('cart-count');
        const subtotalEl = document.getElementById('subtotal');
        const totalEl   = document.getElementById('total');

        if (keranjang.length === 0) {
            container.innerHTML = `
                <div class="empty">
                    ☕<br>Belum ada pesanan<br>
                    <span style="font-size:11px">Pilih menu di sebelah kiri</span>
                </div>`;
            countEl.textContent    = '0 item';
            subtotalEl.textContent = 'Rp 0';
            totalEl.textContent    = 'Rp 0';
            return;
        }

        let total = 0;
        let html  = '';

        keranjang.forEach(item => {
            const subtotal = item.harga * item.qty;
            total += subtotal;

            html += `
                <div class="cart-item">
                    <div style="flex:1">
                        <div class="cart-name">${item.nama}</div>
                        <div class="cart-price-info">
                            Rp ${item.harga.toLocaleString('id-ID')} × ${item.qty}
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="qty-wrap">
                            <button class="qty-btn" onclick="changeQty('${item.id}', -1)">−</button>
                            <span class="qty-val">${item.qty}</span>
                            <button class="qty-btn" onclick="changeQty('${item.id}', 1)">+</button>
                        </div>
                        <span class="cart-subtotal">
                            Rp ${subtotal.toLocaleString('id-ID')}
                        </span>
                    </div>
                </div>`;
        });

        container.innerHTML    = html;
        countEl.textContent    = keranjang.reduce((s, i) => s + i.qty, 0) + ' item';
        subtotalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        totalEl.textContent    = 'Rp ' + total.toLocaleString('id-ID');
    }

    // =====================
    // Filter kategori
    // =====================
    function filterKategori(slug, btn) {
        document.querySelectorAll('.kategori-btn')
            .forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.menu-card').forEach(card => {
            card.style.display =
                (slug === 'semua' || card.dataset.kategori === slug) ? '' : 'none';
        });
    }

    // =====================
    // Search menu
    // =====================
    document.getElementById('search-input').addEventListener('input', function () {
        const q = this.value.toLowerCase();

        document.querySelectorAll('.menu-card').forEach(card => {
            card.style.display =
                card.dataset.nama.includes(q) ? '' : 'none';
        });
    });

    // =====================
    // Proses bayar → Midtrans
    // =====================
    function prosesBayar() {
        const nama    = document.getElementById('input-nama').value;
        const meja    = document.getElementById('select-meja').value;
        const catatan = document.getElementById('input-catatan').value;

        if (!nama)              return showToast('Pilih pelanggan dulu!', 'error');
        if (!meja)              return showToast('Pilih meja dulu!', 'error');
        if (keranjang.length === 0) return showToast('Keranjang masih kosong!', 'error');

        showLoading(true);

        fetch('{{ route("kasir.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                nama_pelanggan: nama,
                meja:           meja,
                catatan:        catatan,
                items:          keranjang,
            }),
        })
        .then(r => r.json())
        .then(data => {
            showLoading(false);

            if (!data.success) {
                showToast('Gagal membuat pesanan!', 'error');
                return;
            }

            // Buka Midtrans Snap
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    fetch('{{ route("kasir.paymentSuccess") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ order_id: result.order_id }),
                    })
                    .then(() => {
                        showToast('Pembayaran berhasil! 🎉', 'success');
                        keranjang = [];
                        renderKeranjang();
                        document.getElementById('select-pelanggan').value = '';
                        document.getElementById('input-nama').value        = '';
                        document.getElementById('select-meja').value       = '';
                        document.getElementById('input-catatan').value     = '';
                    });
                },
                onPending: function() {
                    showToast('Menunggu pembayaran...', '');
                },
                onError: function() {
                    showToast('Pembayaran gagal!', 'error');
                },
                onClose: function() {
                    showToast('Popup ditutup sebelum selesai.', 'error');
                }
            });
        })
        .catch(() => {
            showLoading(false);
            showToast('Terjadi kesalahan server!', 'error');
        });
    }

    // =====================
    // Helper: loading
    // =====================
    function showLoading(show) {
        document.getElementById('loading-overlay').classList.toggle('show', show);
    }

    // =====================
    // Helper: toast notif
    // =====================
    function showToast(msg, type = '') {
        const toast = document.getElementById('toast');
        toast.textContent  = msg;
        toast.className    = 'toast ' + type + ' show';

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }
</script>

</body>
</html>
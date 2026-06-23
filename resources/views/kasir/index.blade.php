INDEX BLADE

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
            grid-template-columns: repeat(5, 1fr);
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
            height: 170px;
            background: #F0E3D5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            overflow: hidden;
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-desc {
            font-size: 11px;
            color: #9E8E84;
            line-height: 1.5;
            margin-bottom: 8px;
            height: 32px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
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
                            <option value="{{ $p->id }}" data-nama="{{ $p->nama_pelanggan }}">
                                #{{ $p->id }} - {{ $p->nama_pelanggan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Pemesan</label>
                    <input type="text" id="input-nama" placeholder="Nama pelanggan" readonly>
                </div>
                <div class="form-group">
                    <label>Jenis Pesanan</label>
                    <select id="select-jenis-pemesanan" onchange="toggleMeja()">
                        <option value="dine_in">Dine In</option>
                        <option value="take_away">Take Away</option>
                    </select>
                </div>
                <div class="form-group" id="wrap-meja">
                    <label>No. Meja</label>
                    <select id="select-meja">
                        <option value="">Pilih Meja</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">Meja {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <input type="text" id="input-catatan" placeholder="Opsional">
                </div>
            </div>
        </div>

        {{-- Kategori --}}
        <div class="kategori-wrap">
            <button class="kategori-btn active" onclick="filterKategori('Semua', this)">Semua</button>
            @foreach($menus->keys() as $kategori)
                <button class="kategori-btn" onclick="filterKategori('{{ $kategori }}', this)">{{ $kategori }}</button>
            @endforeach
        </div>

        {{-- Menu Grid --}}
        <div class="menu-grid" id="menu-grid-container">
            @foreach($menus as $kategori => $items)
                @foreach($items as $menu)
                    <div class="menu-card" data-kategori="{{ $kategori }}" data-nama="{{ strtolower($menu->nama_menu) }}">
                        <div class="menu-img">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}">
                            @else
                                ☕
                            @endif
                        </div>
                        <div class="menu-body">
                            <div class="menu-name">{{ $menu->nama_menu }}</div>
                            <div class="menu-desc">{{ $menu->deskripsi }}</div>
                            <div class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                            <button class="btn-add"
                                onclick="addToCart('{{ $menu->id_menu }}', '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }})">
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
            <button class="btn-pay" id="btn-pay" onclick="pilihMetode()">
                Bayar Sekarang →
            </button>
        </div>
    </div>

</div>

{{-- Modal: Pilih Metode Pembayaran --}}
<div id="modal-pilihan-metode" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9998; justify-content: center; align-items: center;">
    <div style="background: white; padding: 25px; border-radius: 15px; width: 360px; color: #333; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 5px; font-weight: bold; color: #2C1A0E; text-align: center;">Metode Pembayaran</h3>
        <hr style="border: 0.5px solid #eee; margin: 15px 0;">

        <div style="margin-bottom: 15px;">
            <label style="font-weight: 600; font-size: 13px;">Pilih Metode:</label>
            <select id="metode-pembayaran" onchange="toggleFormCash()" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px; font-family: 'Poppins';">
                <option value="qris">QRIS</option>
                <option value="debit">Debit/Kartu</option>
                <option value="cash">Cash/Tunai</option>
            </select>
        </div>

        <div id="form-cash-detail" style="display: none; background: #f9f5f2; padding: 12px; border-radius: 10px; margin-bottom: 15px;">
            <div style="margin-bottom: 10px;">
                <label style="font-size: 12px; font-weight: 600;">Uang Diterima (Rp):</label>
                <input type="text" id="cash-input-bayar" placeholder="Contoh: 50.000" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px; font-family: 'Poppins';">
            </div>
            <div>
                <label style="font-size: 12px; font-weight: 600;">Kembalian:</label>
                <div id="cash-text-kembalian" style="font-size: 16px; font-weight: 700; color: #C0392B; margin-top: 3px;">Rp 0</div>
            </div>
        </div>

        <button type="button" onclick="prosesPembayaranPilihan()" style="background: #C0392B; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 100%; font-weight: bold; font-family: 'Poppins'; margin-bottom: 10px;">
            Konfirmasi & Proses
        </button>

        <button type="button" onclick="tutupModalMetode()" style="background: #9E8E84; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; width: 100%; font-weight: bold; font-family: 'Poppins';">
            Batal
        </button>
    </div>
</div>

{{-- Modal: QRIS --}}
<div id="modal-qris" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 25px; border-radius: 15px; text-align: center; width: 320px; color: #333; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 5px; font-weight: bold; color: #2C1A0E;">Pembayaran QRIS</h3>
        <p style="font-size: 12px; color: #666;" id="qris-order-id">Order ID: -</p>
        <hr style="border: 0.5px solid #eee; margin: 15px 0;">

        <div style="margin: 20px auto; width: 200px; height: 200px; background: #f9f9f9; display: flex; justify-content: center; align-items: center; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;">
            <img id="gambar-qris" src="" alt="Scan QRIS di Sini" style="width: 100%; height: 100%; display: none;">
            <span id="loading-text" style="font-size: 14px; color: #888;">Sedang memuat QRIS...</span>
        </div>

        <h4 style="color: #C0392B; font-weight: bold; margin-top: 10px; font-size: 18px;">Total: Rp <span id="qris-total-harga">0</span></h4>
        <p style="font-size: 11px; color: #999; margin-top: 5px;">Silakan scan menggunakan BCA, GoPay, OVO, Dana, LinkAja, dll.</p>
        <p style="font-size: 12px; color: #9E8E84; margin-top: 10px;" id="qris-status-text">⏳ Menunggu pembayaran...</p>

        <button type="button" onclick="tutupModalQRIS()" style="margin-top: 10px; background: #9E8E84; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; width: 100%; font-weight: bold; font-family: 'Poppins';">
            Tutup / Batalkan
        </button>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let keranjang   = [];

    // =====================
    // Update nama otomatis saat pelanggan dipilih
    // =====================
    function updateNama() {
        const select = document.getElementById('select-pelanggan');
        const opt    = select.options[select.selectedIndex];
        document.getElementById('input-nama').value = opt.getAttribute('data-nama') || '';
    }

    // =====================
    // Tampilkan/sembunyikan pilihan meja sesuai jenis pesanan
    // =====================
    function toggleMeja() {
        const jenis      = document.getElementById('select-jenis-pemesanan').value;
        const wrapMeja    = document.getElementById('wrap-meja');
        const selectMeja  = document.getElementById('select-meja');

        if (jenis === 'take_away') {
            wrapMeja.style.opacity   = '0.4';
            selectMeja.disabled      = true;
            selectMeja.value         = '';
        } else {
            wrapMeja.style.opacity   = '1';
            selectMeja.disabled      = false;
        }
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
        const container  = document.getElementById('cart-items');
        const countEl     = document.getElementById('cart-count');
        const subtotalEl  = document.getElementById('subtotal');
        const totalEl      = document.getElementById('total');

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
    function filterKategori(kategori, btnElemen) {
        document.querySelectorAll('.kategori-btn')
            .forEach(b => b.classList.remove('active'));
        btnElemen.classList.add('active');

        document.querySelectorAll('.menu-card').forEach(card => {
            const kategoriCard = card.getAttribute('data-kategori');
            card.style.display =
                (kategori === 'Semua' || kategoriCard === kategori) ? '' : 'none';
        });

        document.getElementById('search-input').value = '';
    }

    // =====================
    // Search menu
    // =====================
    document.getElementById('search-input').addEventListener('input', function () {
        const q = this.value.toLowerCase();

        document.querySelectorAll('.kategori-btn').forEach(b => b.classList.remove('active'));

        document.querySelectorAll('.menu-card').forEach(card => {
            const namaMenu = card.getAttribute('data-nama');
            card.style.display = namaMenu.includes(q) ? '' : 'none';
        });
    });

    // =====================
    // Modal: pilih metode pembayaran
    // =====================
    function pilihMetode() {
        const pelangganId = document.getElementById('select-pelanggan').value;
        if (!pelangganId) return showToast('Pilih pelanggan dulu!', 'error');

        const jenisPemesanan = document.getElementById('select-jenis-pemesanan').value;
        const meja         = document.getElementById('select-meja').value;
        if (jenisPemesanan === 'dine_in' && !meja) return showToast('Pilih meja dulu!', 'error');

        if (keranjang.length === 0) return showToast('Keranjang masih kosong!', 'error');

        document.getElementById('modal-pilihan-metode').style.display = 'flex';
        document.getElementById('metode-pembayaran').value = 'qris';
        document.getElementById('form-cash-detail').style.display = 'none';
        document.getElementById('cash-input-bayar').value = '';
        document.getElementById('cash-text-kembalian').innerText = 'Rp 0';
    }

    function tutupModalMetode() {
        document.getElementById('modal-pilihan-metode').style.display = 'none';
    }

    function toggleFormCash() {
        const metode   = document.getElementById('metode-pembayaran').value;
        const formCash = document.getElementById('form-cash-detail');
        formCash.style.display = (metode === 'cash') ? 'block' : 'none';
    }

    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   = number_string.split(','),
            sisa    = split[0].length % 3,
            rupiah  = split[0].substr(0, sisa),
            ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    document.getElementById('cash-input-bayar').addEventListener('keyup', function (e) {
        this.value = formatRupiah(this.value, 'Rp. ');
        hitungKembalian();
    });

    function hitungKembalian() {
        const totalDisplay = document.getElementById('total').innerText;
        const totalHarga   = parseInt(totalDisplay.replace(/\D/g, '')) || 0;

        const uangBayarRaw = document.getElementById('cash-input-bayar').value;
        const uangBayar     = parseInt(uangBayarRaw.replace(/\D/g, '')) || 0;

        const kembalian = uangBayar - totalHarga;

        if (uangBayarRaw === '') {
            document.getElementById('cash-text-kembalian').innerText = 'Rp 0';
        } else if (kembalian < 0) {
            document.getElementById('cash-text-kembalian').innerText = 'Uang tidak cukup';
        } else {
            document.getElementById('cash-text-kembalian').innerText = 'Rp ' + kembalian.toLocaleString('id-ID');
        }
    }

    function prosesPembayaranPilihan() {
        const metode = document.getElementById('metode-pembayaran').value;

        if (metode === 'qris') {
            tutupModalMetode();
            prosesBayarQris();
        } else if (metode === 'debit') {
            tutupModalMetode();
            prosesBayarDebit();
        } else if (metode === 'cash') {
            const totalDisplay = document.getElementById('total').innerText;
            const totalHarga    = parseInt(totalDisplay.replace(/\D/g, '')) || 0;
            const uangBayar      = parseInt(document.getElementById('cash-input-bayar').value.replace(/\D/g, '')) || 0;

            if (uangBayar < totalHarga) {
                return showToast('Uang yang dimasukkan kurang dari total harga!', 'error');
            }
            simpanTransaksiCash(totalHarga, uangBayar);
        }
    }

    // =====================
    // Checkout Debit/Kartu via Midtrans Snap (popup dengan banyak pilihan metode)
    // =====================
    function prosesBayarDebit() {
        const nama         = document.getElementById('input-nama').value;
        const jenisPemesanan = document.getElementById('select-jenis-pemesanan').value;
        const meja          = document.getElementById('select-meja').value;
        const catatan        = document.getElementById('input-catatan').value;

        if (!nama)                                          return showToast('Pilih pelanggan dulu!', 'error');
        if (jenisPemesanan === 'dine_in' && !meja)           return showToast('Pilih meja dulu!', 'error');
        if (keranjang.length === 0)                          return showToast('Keranjang masih kosong!', 'error');

        showLoading(true);

        fetch('{{ route("kasir.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                nama_pelanggan: nama,
                jenis_pemesanan: jenisPemesanan,
                meja:           meja,
                catatan:        catatan,
                metode:         'debit',
                items:          keranjang,
            }),
        })
        .then(async r => {
            const data = await r.json();
            if (!r.ok) throw new Error(data.message || 'Gagal membuat pesanan');
            return data;
        })
        .then(data => {
            showLoading(false);

            if (!data.success) {
                showToast(data.message || 'Gagal membuat pesanan!', 'error');
                return;
            }

            snap.pay(data.snap_token, {
                onSuccess: function (result) {
                    fetch('{{ route("kasir.payment") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ order_id: result.order_id }),
                    })
                    .then(() => {
                        showToast('Pembayaran berhasil! 🎉', 'success');
                        resetForm();
                    });
                },
                onPending: function () {
                    showToast('Menunggu pembayaran...', '');
                },
                onError: function () {
                    showToast('Pembayaran gagal!', 'error');
                },
                onClose: function () {
                    showToast('Popup ditutup sebelum selesai.', 'error');
                }
            });
        })
        .catch(err => {
            showLoading(false);
            showToast(err.message || 'Terjadi kesalahan server!', 'error');
        });
    }

    // =====================
    // Checkout QRIS via Midtrans Core API (QR tampil langsung, tanpa popup)
    // =====================
    let qrisPollingInterval = null;
    let qrisOrderIdAktif    = null;

    function prosesBayarQris() {
        const nama          = document.getElementById('input-nama').value;
        const jenisPemesanan  = document.getElementById('select-jenis-pemesanan').value;
        const meja          = document.getElementById('select-meja').value;
        const catatan       = document.getElementById('input-catatan').value;

        if (!nama)                                          return showToast('Pilih pelanggan dulu!', 'error');
        if (jenisPemesanan === 'dine_in' && !meja)             return showToast('Pilih meja dulu!', 'error');
        if (keranjang.length === 0)                          return showToast('Keranjang masih kosong!', 'error');

        showLoading(true);

        fetch('{{ route("kasir.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                nama_pelanggan: nama,
                jenis_pemesanan:  jenisPemesanan,
                meja:           meja,
                catatan:        catatan,
                metode:         'qris',
                items:          keranjang,
            }),
        })
        .then(async r => {
            const data = await r.json();
            if (!r.ok) throw new Error(data.message || 'Gagal membuat pesanan');
            return data;
        })
        .then(data => {
            showLoading(false);

            if (!data.success) {
                showToast(data.message || 'Gagal membuat pesanan!', 'error');
                return;
            }

            tampilkanModalQris(data.order_id, data.qr_url, data.total);
        })
        .catch(err => {
            showLoading(false);
            showToast(err.message || 'Terjadi kesalahan server!', 'error');
        });
    }

    function tampilkanModalQris(orderId, qrUrl, total) {
        qrisOrderIdAktif = orderId;

        document.getElementById('qris-order-id').innerText      = 'Order ID: ' + orderId;
        document.getElementById('qris-total-harga').innerText   = total.toLocaleString('id-ID');
        document.getElementById('qris-status-text').innerText   = '⏳ Menunggu pembayaran...';
        document.getElementById('qris-status-text').style.color = '#9E8E84';

        const imgEl     = document.getElementById('gambar-qris');
        const loadingEl = document.getElementById('loading-text');

        imgEl.style.display     = 'none';
        loadingEl.style.display = 'block';

        imgEl.onload = function () {
            imgEl.style.display     = 'block';
            loadingEl.style.display = 'none';
        };
        imgEl.src = qrUrl;

        document.getElementById('modal-qris').style.display = 'flex';

        mulaiPollingQris(orderId);
    }

    function mulaiPollingQris(orderId) {
        hentikanPollingQris();

        qrisPollingInterval = setInterval(() => {
            fetch(`/kasir/check-status/${orderId}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;

                    if (data.lunas) {
                        hentikanPollingQris();
                        document.getElementById('qris-status-text').innerText   = '✅ Pembayaran berhasil!';
                        document.getElementById('qris-status-text').style.color = '#16A34A';

                        setTimeout(() => {
                            document.getElementById('modal-qris').style.display = 'none';
                            showToast('Pembayaran QRIS berhasil! 🎉', 'success');
                            resetForm();
                        }, 1200);
                    } else if (['deny', 'expire', 'cancel'].includes(data.transaction_status)) {
                        hentikanPollingQris();
                        document.getElementById('qris-status-text').innerText   = '✗ Pembayaran dibatalkan/kedaluwarsa.';
                        document.getElementById('qris-status-text').style.color = '#DC2626';
                    }
                })
                .catch(() => { /* abaikan, coba lagi di polling berikutnya */ });
        }, 4000);
    }

    function hentikanPollingQris() {
        if (qrisPollingInterval) {
            clearInterval(qrisPollingInterval);
            qrisPollingInterval = null;
        }
    }

    // =====================
    // Checkout cash/tunai
    // =====================
    function simpanTransaksiCash(totalHarga, uangBayar) {
        const namaPemesan  = document.getElementById('input-nama').value;
        const jenisPemesanan = document.getElementById('select-jenis-pemesanan').value;
        const noMeja       = document.getElementById('select-meja').value;
        const catatan      = document.getElementById('input-catatan').value;

        showLoading(true);

        fetch('{{ route("kasir.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                nama_pelanggan: namaPemesan,
                jenis_pemesanan:  jenisPemesanan,
                meja:           noMeja,
                catatan:        catatan,
                metode:         'cash',
                uang_diterima:  uangBayar,
                items:          keranjang,
            }),
        })
        .then(async r => {
            const data = await r.json();
            if (!r.ok) throw new Error(data.message || 'Gagal menyimpan transaksi');
            return data;
        })
        .then(data => {
            showLoading(false);

            if (!data.success) {
                showToast('Gagal menyimpan transaksi: ' + (data.message || 'Error tidak diketahui'), 'error');
                return;
            }

            tutupModalMetode();
            showToast('Transaksi cash berhasil disimpan!', 'success');
            resetForm();
        })
        .catch(err => {
            showLoading(false);
            showToast(err.message || 'Terjadi kesalahan jaringan.', 'error');
        });
    }

    function resetForm() {
        keranjang = [];
        renderKeranjang();
        document.getElementById('select-pelanggan').value      = '';
        document.getElementById('input-nama').value             = '';
        document.getElementById('select-jenis-pemesanan').value   = 'dine_in';
        document.getElementById('select-meja').value             = '';
        document.getElementById('select-meja').disabled          = false;
        document.getElementById('wrap-meja').style.opacity       = '1';
        document.getElementById('input-catatan').value           = '';
    }

    // =====================
    // Helper: loading overlay
    // =====================
    function showLoading(show) {
        document.getElementById('loading-overlay').classList.toggle('show', show);
    }

    // =====================
    // Helper: toast notif
    // =====================
    function showToast(msg, type = '') {
        const toast = document.getElementById('toast');
        toast.textContent = msg;
        toast.className   = 'toast ' + type + ' show';

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function tutupModalQRIS() {
        hentikanPollingQris();
        document.getElementById('modal-qris').style.display = 'none';
    }
</script>
</body>
</html>

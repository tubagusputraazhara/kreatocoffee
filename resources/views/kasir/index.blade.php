<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir — Kreato Coffee</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Poppins', sans-serif;
            background:#F5F0EC;
            height:100vh;
            overflow:hidden;
        }

        .wrapper{
            display:flex;
            height:100vh;
        }

        /* =======================
            LEFT SIDE
        ======================= */
        .left{
            flex:1;
            padding:24px;
            overflow-y:auto;
        }

        .header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:24px;
        }

        .title{
            font-size:28px;
            font-weight:700;
            color:#2C1A0E;
        }

        .subtitle{
            font-size:13px;
            color:#9E8E84;
            margin-top:4px;
        }

        .search{
            width:280px;
            padding:12px 16px;
            border:none;
            border-radius:14px;
            background:#fff;
            font-family:'Poppins';
            outline:none;
            font-size:14px;
        }

        .kategori-wrap{
            display:flex;
            gap:10px;
            overflow-x:auto;
            margin-bottom:24px;
        }

        .kategori-wrap::-webkit-scrollbar{
            display:none;
        }

        .kategori-btn{
            padding:10px 18px;
            border:none;
            border-radius:20px;
            background:#fff;
            cursor:pointer;
            white-space:nowrap;
            font-size:13px;
            font-family:'Poppins';
            color:#5C4033;
        }

        .kategori-btn.active{
            background:#C0392B;
            color:#fff;
        }

        .menu-grid{
            display:grid;
            grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));
            gap:18px;
        }

        .menu-card{
            background:#fff;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 4px 16px rgba(0,0,0,0.05);
        }

        .menu-img{
            height:150px;
            background:#F0E3D5;
        }

        .menu-img img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .menu-body{
            padding:16px;
        }

        .menu-name{
            font-size:16px;
            font-weight:600;
            color:#2C1A0E;
            margin-bottom:4px;
        }

        .menu-desc{
            font-size:12px;
            color:#9E8E84;
            line-height:1.5;
            margin-bottom:10px;
            height: 36px;
            overflow: hidden;
        }

        .menu-price{
            color:#C0392B;
            font-size:16px;
            font-weight:700;
            margin-bottom:14px;
        }

        .btn-add{
            width:100%;
            padding:12px;
            border:none;
            border-radius:12px;
            background:#C0392B;
            color:#fff;
            font-family:'Poppins';
            font-weight:600;
            cursor:pointer;
        }

        /* =======================
            FORM PEMESANAN
        ======================= */
        .order-form{
            background:#fff;
            padding:24px;
            border-radius:20px;
            margin-bottom:24px;
            box-shadow:0 4px 16px rgba(0,0,0,0.05);
        }

        .form-title{
            font-size:22px;
            font-weight:700;
            color:#2C1A0E;
            margin-bottom:20px;
        }

        .form-grid{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:18px;
        }

        .form-group label{
            font-size:13px;
            font-weight:600;
            color:#5C4033;
        }

        .form-group input,
        .form-group select{
            width:100%;
            margin-top:8px;
            padding:12px;
            border:1px solid #E5D7CC;
            border-radius:12px;
            font-family:'Poppins';
            outline:none;
        }

        .form-group input[readonly] {
            background-color: #f9f9f9;
            cursor: not-allowed;
        }

        /* =======================
            RIGHT SIDE CART
        ======================= */
        .right{
            width:380px;
            background:#fff;
            border-left:1px solid #EEE3DB;
            display:flex;
            flex-direction:column;
        }

        .cart-header{
            padding:24px;
            border-bottom:1px solid #F0E3D5;
        }

        .cart-title{
            font-size:22px;
            font-weight:700;
            color:#2C1A0E;
        }

        .cart-items{
            flex:1;
            overflow-y:auto;
            padding:20px;
        }

        .cart-item{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:18px;
            gap:10px;
        }

        .cart-name{
            font-size:14px;
            font-weight:600;
            color:#2C1A0E;
        }

        .cart-price-info{
            font-size:12px;
            color:#9E8E84;
            margin-top:2px;
        }

        .qty-wrap{
            display:flex;
            align-items:center;
            gap:12px;
            background: #F5F0EC;
            padding: 4px 8px;
            border-radius: 10px;
        }

        .qty-btn{
            width:24px;
            height:24px;
            border:none;
            border-radius:50%;
            background:#fff;
            cursor:pointer;
            font-size:16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #C0392B;
        }

        .qty-val{
            font-size: 14px;
            font-weight: 600;
        }

        .cart-footer{
            padding:24px;
            border-top:1px solid #F0E3D5;
        }

        .total{
            display:flex;
            justify-content:space-between;
            margin-bottom:20px;
        }

        .total-label{
            font-size:14px;
            color:#9E8E84;
        }

        .total-value{
            font-size:24px;
            font-weight:700;
            color:#2C1A0E;
        }

        .btn-pay{
            width:100%;
            padding:16px;
            border:none;
            border-radius:16px;
            background:#C0392B;
            color:#fff;
            font-size:16px;
            font-weight:700;
            font-family:'Poppins';
            cursor:pointer;
        }

        .empty{
            text-align:center;
            color:#B0A09A;
            margin-top:80px;
            font-size:14px;
        }
    </style>
</head>

<body>

<div class="wrapper">

    <!-- LEFT -->
    <div class="left">

        <!-- FORM DATA PEMESANAN -->
        <div class="order-form">
            <div class="form-title">Data Pemesanan</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>ID Pelanggan</label>
                    <select id="select-id-pelanggan" onchange="updateNama()">
                        <option value="">Pilih ID Pelanggan</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}" data-nama="{{ $p->nama_pelanggan }}">
                                {{ $p->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" id="input-nama-pelanggan" placeholder="Nama akan muncul otomatis" readonly>
                </div>

                <div class="form-group">
                    <label>No Meja</label>
                    <select id="select-meja">
                        <option value="">Pilih Meja</option>
                        @for($i = 1; $i <= 20; $i++)
                            <option value="{{ $i }}">Meja {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <input type="text" id="input-catatan" placeholder="Tambahkan catatan">
                </div>
            </div>
        </div>

        <!-- HEADER -->
        <div class="header">
            <div>
                <div class="title">Kasir Kreato Coffee</div>
                <div class="subtitle">Point of Sales System</div>
            </div>
            <input type="text" class="search" placeholder="Cari menu...">
        </div>

        <!-- KATEGORI -->
        <div class="kategori-wrap">
            <button class="kategori-btn active">Semua</button>
            @foreach($menus->keys() as $kategori)
                <button class="kategori-btn">{{ $kategori }}</button>
            @endforeach
        </div>

        <!-- MENU -->
        <div class="menu-grid">
            @foreach($menus as $items)
                @foreach($items as $menu)
                    <div class="menu-card">
                        <div class="menu-img">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}">
                            @else
                                <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:48px;">☕</div>
                            @endif
                        </div>
                        <div class="menu-body">
                            <div class="menu-name">{{ $menu->nama_menu }}</div>
                            <div class="menu-desc">{{ $menu->deskripsi }}</div>
                            <div class="menu-price">Rp {{ number_format($menu->harga,0,',','.') }}</div>
                            <button class="btn-add" onclick="addToCart('{{ $menu->id_menu }}', '{{ $menu->nama_menu }}', {{ $menu->harga }})">
                                Tambah
                            </button>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- RIGHT SIDE: KERANJANG -->
    <div class="right">
        <div class="cart-header">
            <div class="cart-title">Keranjang</div>
        </div>

        <div class="cart-items" id="cart-items">
            <!-- Item akan muncul di sini -->
            <div class="empty">Belum ada pesanan</div>
        </div>

        <div class="cart-footer">
            <div class="total">
                <div class="total-label">Total</div>
                <div class="total-value" id="total">Rp 0</div>
            </div>
            <button class="btn-pay" onclick="prosesBayar()">Bayar Sekarang</button>
        </div>
    </div>
</div>

<script>
    // 1. Data Penampung (State)
    let keranjang = [];

    // 2. Update Nama Pelanggan Otomatis
    function updateNama() {
        const select = document.getElementById('select-id-pelanggan');
        const inputNama = document.getElementById('input-nama-pelanggan');
        const selectedOption = select.options[select.selectedIndex];
        const nama = selectedOption.getAttribute('data-nama');
        inputNama.value = nama ? nama : "";
    }

    // 3. Fungsi Tambah ke Keranjang
    function addToCart(id, nama, harga) {
        // Cek apakah item sudah ada di keranjang
        const itemIndex = keranjang.findIndex(item => item.id === id);

        if (itemIndex > -1) {
            // Jika ada, tambah quantity
            keranjang[itemIndex].qty += 1;
        } else {
            // Jika belum ada, push object baru
            keranjang.push({
                id: id,
                nama: nama,
                harga: harga,
                qty: 1
            });
        }
        renderKeranjang();
    }

    // 4. Ubah Quantity (Tambah/Kurang)
    function changeQty(id, delta) {
        const itemIndex = keranjang.findIndex(item => item.id === id);
        if (itemIndex > -1) {
            keranjang[itemIndex].qty += delta;
            
            // Jika qty 0, hapus dari keranjang
            if (keranjang[itemIndex].qty <= 0) {
                keranjang.splice(itemIndex, 1);
            }
        }
        renderKeranjang();
    }

    // 5. Render Tampilan Keranjang
    function renderKeranjang() {
        const cartContainer = document.getElementById('cart-items');
        const totalDisplay = document.getElementById('total');
        
        if (keranjang.length === 0) {
            cartContainer.innerHTML = '<div class="empty">Belum ada pesanan</div>';
            totalDisplay.innerText = 'Rp 0';
            return;
        }

        let html = '';
        let totalHarga = 0;

        keranjang.forEach(item => {
            const subtotal = item.harga * item.qty;
            totalHarga += subtotal;

            html += `
                <div class="cart-item">
                    <div>
                        <div class="cart-name">${item.nama}</div>
                        <div class="cart-price-info">Rp ${item.harga.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="qty-wrap">
                        <button class="qty-btn" onclick="changeQty('${item.id}', -1)">-</button>
                        <span class="qty-val">${item.qty}</span>
                        <button class="qty-btn" onclick="changeQty('${item.id}', 1)">+</button>
                    </div>
                </div>
            `;
        });

        cartContainer.innerHTML = html;
        totalDisplay.innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
    }

    // 6. Proses Bayar (Kirim ke Server)
    function prosesBayar() {
        const pelangganId = document.getElementById('select-id-pelanggan').value;
        const meja = document.getElementById('select-meja').value;

        if (!pelangganId) return alert("Pilih pelanggan!");
        if (keranjang.length === 0) return alert("Keranjang kosong!");

        const dataPesanan = {
            pelanggan_id: pelangganId,
            meja: meja,
            items: keranjang,
            catatan: document.getElementById('input-catatan').value
        };

        console.log("Mengirim data ke server:", dataPesanan);
        alert("Pesanan Berhasil! Cek console untuk data JSON.");
        
        // Reset keranjang setelah bayar
        keranjang = [];
        renderKeranjang();
    }
</script>

</body>
</html>
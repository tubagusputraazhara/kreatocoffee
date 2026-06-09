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
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
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
            padding:10px 20px;
            border:none;
            border-radius:20px;
            background:#fff;
            cursor:pointer;
            white-space:nowrap;
            font-size:13px;
            font-family:'Poppins';
            font-weight: 500;
            color:#5C4033;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .menu-img{
            height:160px; 
            width: 100%;
            background:#F0E3D5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden; 
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis; 
        }

        .menu-desc{
            font-size:12px;
            color:#9E8E84;
            line-height:1.5;
            margin-bottom:12px;
            height: 36px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
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
            transition: background 0.2s ease;
        }

        .btn-add:hover {
            background: #A62F24;
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
            transition: background 0.2s ease;
        }

        .btn-pay:hover{
            background: #A62F24;
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
    <div class="left">
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

        <div class="header">
            <div>
                <div class="title">Kasir Kreato Coffee</div>
                <div class="subtitle">Point of Sales System</div>
            </div>
            <input type="text" id="search-input" class="search" placeholder="Cari menu..." onkeyup="filterSearch()">
        </div>

        <div class="kategori-wrap">
            <button class="kategori-btn active" onclick="filterKategori('Semua', this)">Semua</button>
            @foreach($menus->keys() as $kategori)
                <button class="kategori-btn" onclick="filterKategori('{{ $kategori }}', this)">{{ $kategori }}</button>
            @endforeach
        </div>

        <div class="menu-grid" id="menu-grid-container">
            @foreach($menus as $kategori => $items)
                @foreach($items as $menu)
                    <div class="menu-card" data-kategori="{{ $kategori }}" data-nama="{{ strtolower($menu->nama_menu) }}">
                        <div class="menu-img">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}">
                            @else
                                <div style="font-size:48px;">☕</div>
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

    <div class="right">
        <div class="cart-header">
            <div class="cart-title">Keranjang</div>
        </div>

        <div class="cart-items" id="cart-items">
            <div class="empty">Belum ada pesanan</div>
        </div>

        <div class="cart-footer">
            <div class="total">
                <div class="total-label">Total</div>
                <div class="total-value" id="total">Rp 0</div>
            </div>
            <button class="btn-pay" onclick="pilihMetode()">Bayar Sekarang</button>
        </div>
    </div>
</div>

<div id="modal-pilihan-metode" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9998; justify-content: center; align-items: center;">
    <div style="background: white; padding: 25px; border-radius: 15px; width: 360px; color: #333; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 5px; font-weight: bold; color: #2C1A0E; text-align: center;">Metode Pembayaran</h3>
        <hr style="border: 0.5px solid #eee; margin: 15px 0;">
        
        <div style="margin-bottom: 15px;">
            <label style="font-weight: 600; font-size: 13px;">Pilih Metode:</label>
            <select id="metode-pembayaran" onchange="toggleFormCash()" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px; font-family: 'Poppins';">
                <option value="qris">QRIS</option>
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
        
        <button type="button" onclick="tutupModalQRIS()" style="margin-top: 20px; background: #9E8E84; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; width: 100%; font-weight: bold; font-family: 'Poppins';">
            Tutup / Batalkan
        </button>
    </div>
</div>

<script>
    let keranjang = [];

    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    document.getElementById('cash-input-bayar').addEventListener('keyup', function(e) {
        this.value = formatRupiah(this.value, 'Rp. ');
        hitungKembalian();
    });

    function filterKategori(kategori, btnElemen) {
        const tombols = document.querySelectorAll('.kategori-btn');
        tombols.forEach(btn => btn.classList.remove('active'));
        btnElemen.classList.add('active');

        const cards = document.querySelectorAll('.menu-card');
        cards.forEach(card => {
            const kategoriCard = card.getAttribute('data-kategori');
            if (kategori === 'Semua' || kategoriCard === kategori) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        document.getElementById('search-input').value = "";
    }

    function filterSearch() {
        const kataKunci = document.getElementById('search-input').value.toLowerCase();
        const cards = document.querySelectorAll('.menu-card');
        
        const tombols = document.querySelectorAll('.kategori-btn');
        tombols.forEach(btn => btn.classList.remove('active'));
        tombols[0].classList.add('active');

        cards.forEach(card => {
            const namaMenu = card.getAttribute('data-nama');
            if(namaMenu.includes(kataKunci)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function updateNama() {
        const select = document.getElementById('select-id-pelanggan');
        const inputNama = document.getElementById('input-nama-pelanggan');
        const selectedOption = select.options[select.selectedIndex];
        const nama = selectedOption.getAttribute('data-nama');
        inputNama.value = nama ? nama : "";
    }

    function addToCart(id, nama, harga) {
        const itemIndex = keranjang.findIndex(item => item.id === id);
        if (itemIndex > -1) {
            keranjang[itemIndex].qty += 1;
        } else {
            keranjang.push({ id: id, nama: nama, harga: harga, qty: 1 });
        }
        renderKeranjang();
    }

    function changeQty(id, delta) {
        const itemIndex = keranjang.findIndex(item => item.id === id);
        if (itemIndex > -1) {
            keranjang[itemIndex].qty += delta;
            if (keranjang[itemIndex].qty <= 0) {
                keranjang.splice(itemIndex, 1);
            }
        }
        renderKeranjang();
    }

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

    function pilihMetode() {
        const pelangganId = document.getElementById('select-id-pelanggan').value;
        if (!pelangganId) return alert("Pilih pelanggan terlebih dahulu!");
        if (keranjang.length === 0) return alert("Keranjang belanja masih kosong!");

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
        const metode = document.getElementById('metode-pembayaran').value;
        const formCash = document.getElementById('form-cash-detail');
        if (metode === 'cash') {
            formCash.style.display = 'block';
        } else {
            formCash.style.display = 'none';
        }
    }

    function hitungKembalian() {
        let totalDisplay = document.getElementById('total').innerText;
        let totalHarga = parseInt(totalDisplay.replace(/\D/g, '')) || 0;
        
        let uangBayarRaw = document.getElementById('cash-input-bayar').value;
        let uangBayar = parseInt(uangBayarRaw.replace(/\D/g, '')) || 0;
        
        let kembalian = uangBayar - totalHarga;
        if (uangBayarRaw === "") {
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
            generateQRIS();
        } else if (metode === 'cash') {
            let totalDisplay = document.getElementById('total').innerText;
            let totalHarga = parseInt(totalDisplay.replace(/\D/g, '')) || 0;
            let uangBayar = parseInt(document.getElementById('cash-input-bayar').value.replace(/\D/g, '')) || 0;

            if (uangBayar < totalHarga) {
                return alert("Uang yang dimasukkan kurang dari total harga!");
            }
            simpanTransaksiCash(totalHarga, uangBayar);
        }
    }

    function simpanTransaksiCash(totalHarga, uangBayar) {
        const namaPemesan = document.getElementById('input-nama-pelanggan').value;
        const noMeja = document.getElementById('select-meja').value;
        const catatan = document.getElementById('input-catatan').value;

        fetch('/kasir/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                nama_pemesan: namaPemesan,
                no_meja: noMeja,
                catatan: catatan,
                total_harga: totalHarga,
                uang_diterima: uangBayar,
                // Mengubah 'success' menjadi '1' agar muat di kolom status
                status: '1', 
                items: keranjang
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Transaksi Cash Berhasil Disimpan!');
                tutupModalMetode();
                keranjang = [];
                renderKeranjang();
                document.getElementById('select-id-pelanggan').value = '';
                document.getElementById('input-nama-pelanggan').value = '';
                document.getElementById('select-meja').value = '';
                document.getElementById('input-catatan').value = '';
            } else {
                alert('Gagal menyimpan transaksi: ' + (data.message || 'Error tidak diketahui'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan.');
        });
    }
    function generateQRIS() {
        const pelangganId = document.getElementById('select-id-pelanggan').value;
        if (!pelangganId) return alert("Pilih pelanggan terlebih dahulu!");
        if (keranjang.length === 0) return alert("Keranjang belanja masih kosong!");

        let totalDisplay = document.getElementById('total').innerText;
        let totalHarga = totalDisplay.replace(/\D/g, ''); 

        document.getElementById('modal-qris').style.display = 'flex';
        document.getElementById('gambar-qris').style.display = 'none';
        document.getElementById('loading-text').style.display = 'block';
        document.getElementById('loading-text').innerText = "Sedang memuat QRIS...";
        document.getElementById('qris-total-harga').innerText = Number(totalHarga).toLocaleString('id-ID');

        fetch('/kasir/proses-qris', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ total_harga: totalHarga })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('qris-order-id').innerText = "Order ID: " + data.order_id;
                let qrServerUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(data.qr_string)}`;
                let imgElement = document.getElementById('gambar-qris');
                let loadingText = document.getElementById('loading-text');
                
                imgElement.src = qrServerUrl;
                imgElement.onload = function() {
                    imgElement.style.display = 'block';
                    loadingText.style.display = 'none';
                };
            } else {
                alert('Gagal mengambil kode QRIS: ' + data.message);
                tutupModalQRIS();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghubungi server.');
            tutupModalQRIS();
        });
    }

    function tutupModalQRIS() {
        document.getElementById('modal-qris').style.display = 'none';
    }
</script>
</body>
</html>
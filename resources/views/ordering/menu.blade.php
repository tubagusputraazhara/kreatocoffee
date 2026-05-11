<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Pilih Menu — Kreato Coffee</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0,0,0,0.08);
        }

        .topbar {
            background: #C0392B;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
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

        .cart-btn {
            background: rgba(255,255,255,0.18);
            border: none;
            border-radius: 20px;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #fff;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
        }

        .cart-badge {
            background: #fff;
            color: #C0392B;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tabs-wrap {
            background: rgba(255,255,255,0.92);
            padding: 12px 14px;
            display: flex;
            gap: 8px;
            overflow-x: auto;
            border-bottom: 1px solid #EDE0D8;
            position: sticky;
            top: 64px;
            z-index: 90;
            backdrop-filter: blur(10px);
        }

        .tabs-wrap::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-family: 'Poppins', sans-serif;
            border: 1.5px solid #EDE0D8;
            background: #FDFAF8;
            color: #9E8E84;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .tab-btn.active {
            background: #C0392B;
            color: #fff;
            border-color: #C0392B;
        }

        .content-wrap {
            padding-bottom: 110px;
        }

        .kategori-section {
            padding: 0 14px 12px;
        }

        .kategori-title {
            font-size: 12px;
            font-weight: 600;
            color: #5C4033;
            padding: 18px 0 12px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .menu-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            transition: transform 0.15s ease;
        }

        .menu-card:active {
            transform: scale(0.98);
        }

        .menu-img {
            width: 100%;
            height: 110px;
            background: #F5E8D8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            overflow: hidden;
        }

        .menu-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-body {
            padding: 12px;
        }

        .menu-name {
            font-size: 13px;
            font-weight: 600;
            color: #2C1A0E;
            margin-bottom: 3px;
        }

        .menu-desc {
            font-size: 11px;
            color: #9E8E84;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .menu-price {
            font-size: 13px;
            color: #C0392B;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .qty-ctrl {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1.5px solid #EDE0D8;
            background: #FDFAF8;
            color: #5C4033;
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            line-height: 1;
        }

        .qty-btn.add {
            background: #C0392B;
            border-color: #C0392B;
            color: #fff;
        }

        .qty-num {
            font-size: 14px;
            font-weight: 600;
            color: #2C1A0E;
            min-width: 20px;
            text-align: center;
        }

        .cart-bar {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 430px;
            background: #C0392B;
            padding: 14px 16px calc(14px + env(safe-area-inset-bottom));
            display: none;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 -6px 18px rgba(192,57,43,0.28);
            z-index: 100;
            border-radius: 18px 18px 0 0;
        }

        .cart-bar.visible {
            display: flex;
        }

        .cart-bar-count {
            font-size: 12px;
            color: rgba(255,255,255,0.8);
        }

        .cart-bar-total {
            font-size: 17px;
            font-weight: 600;
            color: #fff;
        }

        .btn-checkout {
            background: #fff;
            color: #C0392B;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 200;
            align-items: flex-end;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 28px 28px 0 0;
            width: 100%;
            max-width: 430px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 20px 16px 40px;
            animation: slideUp 0.2s ease;
        }

        .modal-handle {
            width: 40px;
            height: 4px;
            background: #EDE0D8;
            border-radius: 2px;
            margin: 0 auto 16px;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 600;
            color: #2C1A0E;
            margin-bottom: 16px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #F5EDE8;
            gap: 8px;
        }

        .cart-item-name {
            font-size: 13px;
            font-weight: 500;
            color: #2C1A0E;
        }

        .cart-item-price {
            font-size: 11px;
            color: #9E8E84;
            margin-top: 2px;
        }

        .cart-item-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .cart-item-total {
            font-size: 13px;
            font-weight: 600;
            color: #C0392B;
            min-width: 70px;
            text-align: right;
        }

        .modal-footer {
            margin-top: 16px;
        }

        .modal-total-label {
            font-size: 12px;
            color: #9E8E84;
        }

        .modal-total-val {
            font-size: 20px;
            font-weight: 600;
            color: #2C1A0E;
        }

        .btn-to-checkout {
            width: 100%;
            margin-top: 16px;
            padding: 14px;
            background: #C0392B;
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
        }

        .empty-cart {
            text-align: center;
            padding: 32px 0;
            color: #B0A09A;
            font-size: 13px;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (min-width: 768px) {
            body {
                padding: 20px 0;
            }

            .app-container {
                border-radius: 28px;
                min-height: 95vh;
            }
        }
    </style>
</head>
<body>

<div class="app-container">

    <div class="topbar">
        <div>
            <p class="topbar-title">Kreato Coffee</p>
            <p class="topbar-sub">
                {{ session('no_meja') }} — {{ session('nama_pemesan') }}
            </p>
        </div>

        <button class="cart-btn" onclick="toggleModal()">
            🛒 Keranjang
            <span class="cart-badge" id="cart-count">0</span>
        </button>
    </div>

    <div class="tabs-wrap">
        <button class="tab-btn active" onclick="filterKategori('semua', this)">
            Semua
        </button>

        @foreach($menus->keys() as $kategori)
            <button class="tab-btn"
                onclick="filterKategori('{{ Str::slug($kategori) }}', this)">
                {{ $kategori }}
            </button>
        @endforeach
    </div>

    <div class="content-wrap">

        @foreach($menus as $kategori => $items)

            <div class="kategori-section"
                data-kategori="{{ Str::slug($kategori) }}">

                <p class="kategori-title">{{ $kategori }}</p>

                <div class="menu-grid">

                    @foreach($items as $menu)

                        <div class="menu-card">

                            <div class="menu-img">

                                @if($menu->gambar)
                                    <img
                                        src="{{ asset('storage/' . $menu->gambar) }}"
                                        alt="{{ $menu->nama_menu }}"
                                    >
                                @else
                                    ☕
                                @endif

                            </div>

                            <div class="menu-body">

                                <p class="menu-name">
                                    {{ $menu->nama_menu }}
                                </p>

                                @if($menu->deskripsi)
                                    <p class="menu-desc">
                                        {{ Str::limit($menu->deskripsi, 35) }}
                                    </p>
                                @endif

                                <p class="menu-price">
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                </p>

                                <div class="qty-ctrl">

                                    <button
                                        class="qty-btn"
                                        onclick="removeFromCart('{{ $menu->id_menu }}', '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }})">
                                        −
                                    </button>

                                    <span class="qty-num" id="qty-{{ $menu->id_menu }}">
                                        0
                                    </span>

                                    <button
                                        class="qty-btn add"
                                        onclick="addToCart('{{ $menu->id_menu }}', '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }})">
                                        +
                                    </button>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endforeach

    </div>

    <div class="cart-bar" id="cart-bar">

        <div>
            <p class="cart-bar-count" id="bar-count">0 item</p>
            <p class="cart-bar-total" id="bar-total">Rp 0</p>
        </div>

        <button class="btn-checkout" onclick="goCheckout()">
            Bayar →
        </button>

    </div>

    <div class="modal-overlay" id="modal-overlay" onclick="closeModalOutside(event)">

        <div class="modal">

            <div class="modal-handle"></div>

            <p class="modal-title">🛒 Keranjang Kamu</p>

            <div id="modal-items"></div>

            <div class="modal-footer">
                <p class="modal-total-label">Total Pembayaran</p>
                <p class="modal-total-val" id="modal-total">Rp 0</p>
            </div>

            <button class="btn-to-checkout" onclick="goCheckout()">
                Lanjut Bayar →
            </button>

        </div>

    </div>

</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let cart = @json($cart);

    function initCart() {
        Object.entries(cart).forEach(([id, item]) => {
            const el = document.getElementById('qty-' + id);

            if (el) {
                el.textContent = item.qty;
            }
        });

        updateUI();
    }

    function addToCart(id, nama, harga) {
        fetch('{{ route("order.addToCart") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                id_menu: id,
                nama_menu: nama,
                harga: harga
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                cart = data.cart;

                const el = document.getElementById('qty-' + id);

                if (el) {
                    el.textContent = cart[id]?.qty || 0;
                }

                updateUI();
            }
        });
    }

    function removeFromCart(id, nama, harga) {

        if (!cart[id] || cart[id].qty <= 0) return;

        fetch('{{ route("order.removeFromCart") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                id_menu: id,
                nama_menu: nama,
                harga: harga
            })
        })
        .then(r => r.json())
        .then(data => {

            if (data.success) {

                cart = data.cart;

                const el = document.getElementById('qty-' + id);

                if (el) {
                    el.textContent = cart[id]?.qty || 0;
                }

                updateUI();
            }
        });
    }

    function updateUI() {

        const items = Object.values(cart);

        const count = items.reduce((s, i) => s + i.qty, 0);

        const total = items.reduce((s, i) => s + (i.harga * i.qty), 0);

        document.getElementById('cart-count').textContent = count;

        const bar = document.getElementById('cart-bar');

        if (count > 0) {

            bar.classList.add('visible');

            document.getElementById('bar-count').textContent =
                count + ' item dipilih';

            document.getElementById('bar-total').textContent =
                'Rp ' + total.toLocaleString('id-ID');

        } else {

            bar.classList.remove('visible');
        }

        document.getElementById('modal-total').textContent =
            'Rp ' + total.toLocaleString('id-ID');

        const container = document.getElementById('modal-items');

        if (items.length === 0) {

            container.innerHTML =
                '<p class="empty-cart">Keranjang masih kosong ☕</p>';

        } else {

            container.innerHTML = items.map(item => `
                <div class="cart-item">

                    <div style="flex:1">
                        <p class="cart-item-name">${item.nama_menu}</p>
                        <p class="cart-item-price">
                            Rp ${Number(item.harga).toLocaleString('id-ID')}
                        </p>
                    </div>

                    <div class="cart-item-right">

                        <button class="qty-btn"
                            onclick="removeFromCart('${item.id_menu}','${item.nama_menu}',${item.harga})">
                            −
                        </button>

                        <span class="qty-num">${item.qty}</span>

                        <button class="qty-btn add"
                            onclick="addToCart('${item.id_menu}','${item.nama_menu}',${item.harga})">
                            +
                        </button>

                        <span class="cart-item-total">
                            Rp ${(item.harga * item.qty).toLocaleString('id-ID')}
                        </span>

                    </div>

                </div>
            `).join('');
        }
    }

    function filterKategori(slug, btn) {

        document.querySelectorAll('.tab-btn')
            .forEach(b => b.classList.remove('active'));

        btn.classList.add('active');

        document.querySelectorAll('.kategori-section')
            .forEach(sec => {

                sec.style.display =
                    (slug === 'semua' || sec.dataset.kategori === slug)
                    ? ''
                    : 'none';
            });
    }

    function toggleModal() {
        updateUI();

        document.getElementById('modal-overlay')
            .classList.toggle('open');
    }

    function closeModalOutside(e) {

        if (e.target === document.getElementById('modal-overlay')) {

            document.getElementById('modal-overlay')
                .classList.remove('open');
        }
    }

    function goCheckout() {

        if (Object.keys(cart).length === 0) {
            alert('Keranjang masih kosong!');
            return;
        }

        window.location.href = '{{ route("order.checkout") }}';
    }

    initCart();
</script>

</body>
</html>
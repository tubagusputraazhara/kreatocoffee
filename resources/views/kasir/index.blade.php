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
            font-weight:500;
            color:#2C1A0E;
        }

        .cart-price{
            font-size:12px;
            color:#9E8E84;
            margin-top:4px;
        }

        .qty-wrap{
            display:flex;
            align-items:center;
            gap:10px;
        }

        .qty-btn{
            width:28px;
            height:28px;
            border:none;
            border-radius:50%;
            background:#F5F0EC;
            cursor:pointer;
            font-size:16px;
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

        <div class="header">

            <div>
                <div class="title">Kasir Kreato Coffee</div>
                <div class="subtitle">Point of Sales System</div>
            </div>

            <input
                type="text"
                class="search"
                placeholder="Cari menu..."
            >

        </div>

        <div class="kategori-wrap">

            <button class="kategori-btn active">
                Semua
            </button>

            @foreach($menus->keys() as $kategori)

                <button class="kategori-btn">
                    {{ $kategori }}
                </button>

            @endforeach

        </div>

        <div class="menu-grid">

            @foreach($menus as $items)

                @foreach($items as $menu)

                    <div class="menu-card">

                        <div class="menu-img">

                            @if($menu->gambar)

                                <img src="{{ asset('storage/' . $menu->gambar) }}">

                            @else

                                <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:48px;">
                                    ☕
                                </div>

                            @endif

                        </div>

                        <div class="menu-body">

                            <div class="menu-name">
                                {{ $menu->nama_menu }}
                            </div>

                            <div class="menu-desc">
                                {{ $menu->deskripsi }}
                            </div>

                            <div class="menu-price">
                                Rp {{ number_format($menu->harga,0,',','.') }}
                            </div>

                            <button
                                class="btn-add"
                                onclick="addToCart(
                                    '{{ $menu->id_menu }}',
                                    '{{ $menu->nama_menu }}',
                                    '{{ $menu->harga }}'
                                )"
                            >
                                Tambah
                            </button>

                        </div>

                    </div>

                @endforeach

            @endforeach

        </div>

    </div>

    <!-- RIGHT -->
    <div class="right">

        <div class="cart-header">

            <div class="cart-title">
                Keranjang
            </div>

        </div>

        <div class="cart-items" id="cart-items">

            <div class="empty">
                Belum ada pesanan
            </div>

        </div>

        <div class="cart-footer">

            <div class="total">

                <div class="total-label">
                    Total
                </div>

                <div class="total-value" id="total">
                    Rp 0
                </div>

            </div>

            <button class="btn-pay">
                Bayar Sekarang
            </button>

        </div>

    </div>

</div>

<script>

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

let cart = {};

function addToCart(id, nama, harga){

    fetch('{{ route("kasir.addToCart") }}',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':csrfToken
        },

        body:JSON.stringify({
            id_menu:id,
            nama_menu:nama,
            harga:harga
        })

    })
    .then(res => res.json())
    .then(data => {

        cart = data.cart;

        renderCart();

    });

}

function removeFromCart(id){

    fetch('{{ route("kasir.removeFromCart") }}',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':csrfToken
        },

        body:JSON.stringify({
            id_menu:id
        })

    })
    .then(res => res.json())
    .then(data => {

        cart = data.cart;

        renderCart();

    });

}

function renderCart(){

    const container = document.getElementById('cart-items');

    const totalEl = document.getElementById('total');

    const items = Object.values(cart);

    if(items.length === 0){

        container.innerHTML = `
            <div class="empty">
                Belum ada pesanan
            </div>
        `;

        totalEl.innerHTML = 'Rp 0';

        return;
    }

    let total = 0;

    container.innerHTML = items.map(item => {

        total += item.harga * item.qty;

        return `
            <div class="cart-item">

                <div>

                    <div class="cart-name">
                        ${item.nama_menu}
                    </div>

                    <div class="cart-price">
                        Rp ${Number(item.harga).toLocaleString('id-ID')}
                    </div>

                </div>

                <div class="qty-wrap">

                    <button
                        class="qty-btn"
                        onclick="removeFromCart('${item.id_menu}')"
                    >
                        -
                    </button>

                    <div>
                        ${item.qty}
                    </div>

                </div>

            </div>
        `;

    }).join('');

    totalEl.innerHTML =
        'Rp ' + total.toLocaleString('id-ID');

}

</script>

</body>
</html>
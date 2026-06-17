<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Menu;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Services\JurnalService;

class OrderingController extends Controller
{
    public function index()
    {
        $daftarMeja = [
            'Meja 1',  'Meja 2',  'Meja 3',  'Meja 4',  'Meja 5',
            'Meja 6',  'Meja 7',  'Meja 8',  'Meja 9',  'Meja 10',
            'Meja 11', 'Meja 12', 'Meja 13', 'Meja 14', 'Meja 15',
            'Meja 16', 'Meja 17', 'Meja 18', 'Meja 19', 'Meja 20',
            'Meja 21', 'Meja 22', 'Meja 23', 'Meja 24', 'Meja 25',
            'Meja 26', 'Meja 27', 'Meja 28', 'Meja 29', 'Meja 30',
        ];

        return view('ordering.index', compact('daftarMeja'));
    }

    public function storeInfo(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:100',
            'no_meja'      => 'required|string',
            'no_wa'        => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:100',
        ]);

        session([
            'nama_pemesan' => $request->nama_pemesan,
            'no_meja'      => $request->no_meja,
            'no_wa'        => $request->no_wa,
            'email'        => $request->email,
            'cart'         => [],
        ]);

        return redirect()->route('order.menu');
    }

    public function menu()
    {
        if (!session('nama_pemesan')) {
            return redirect()->route('order.index');
        }

        $menus = Menu::all()->groupBy('kategori');
        $cart  = session('cart', []);

        return view('ordering.menu', compact('menus', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $cart = session('cart', []);
        $id   = $request->id_menu;

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += 1;
        } else {
            $cart[$id] = [
                'id_menu'   => $id,
                'nama_menu' => $request->nama_menu,
                'harga'     => $request->harga,
                'qty'       => 1,
            ];
        }

        session(['cart' => $cart]);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return response()->json([
            'success' => true,
            'cart'    => $cart,
            'total'   => $total,
            'count'   => collect($cart)->sum('qty'),
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session('cart', []);
        $id   = $request->id_menu;

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] > 1) {
                $cart[$id]['qty'] -= 1;
            } else {
                unset($cart[$id]);
            }
        }

        session(['cart' => $cart]);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return response()->json([
            'success' => true,
            'cart'    => $cart,
            'total'   => $total,
            'count'   => collect($cart)->sum('qty'),
        ]);
    }

    public function updateCart(Request $request)
    {
        $cart = session('cart', []);
        $id   = $request->id_menu;
        $qty  = (int) $request->qty;

        if ($qty <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id]['qty'] = $qty;
        }

        session(['cart' => $cart]);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return response()->json([
            'success' => true,
            'cart'    => $cart,
            'total'   => $total,
            'count'   => collect($cart)->sum('qty'),
        ]);
    }

    public function checkout()
    {
        if (!session('nama_pemesan') || empty(session('cart'))) {
            return redirect()->route('order.index');
        }

        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return view('ordering.checkout', compact('cart', 'total'));
    }

    public function payment(Request $request)
    {
        if (!session('nama_pemesan') || empty(session('cart'))) {
            return redirect()->route('order.index');
        }

        $cart    = session('cart', []);
        $total   = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
        $catatan = $request->catatan;

        $kodePemesanan = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

        $pemesanan = Pemesanan::create([
            'kode_pemesanan' => $kodePemesanan,
            'nama_pemesan'   => session('nama_pemesan'),
            'no_meja'        => session('no_meja'),
            'no_wa'          => session('no_wa'),
            'email'          => session('email'),
            'sumber'         => 'customer',
            'total_harga'    => $total,
            'status'         => 'pending',
            'jurnal_dibuat'  => false,
            'catatan'        => $catatan,
            'order_id'       => $kodePemesanan,
        ]);

        foreach ($cart as $item) {
            DetailPemesanan::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'id_menu'      => $item['id_menu'],
                'nama_menu'    => $item['nama_menu'],
                'harga_satuan' => $item['harga'],
                'qty'          => $item['qty'],
                'subtotal'     => $item['harga'] * $item['qty'],
            ]);
        }

        \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $itemDetails = collect($cart)->map(fn($item) => [
            'id'       => $item['id_menu'],
            'price'    => (int) $item['harga'],
            'quantity' => $item['qty'],
            'name'     => substr($item['nama_menu'], 0, 50),
        ])->values()->toArray();

        $params = [
            'transaction_details' => [
                'order_id'     => $kodePemesanan,
                'gross_amount' => (int) $total,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => session('nama_pemesan'),
                'phone'      => session('no_wa') ?? '',
                'email'      => session('email') ?? 'guest@kreatocoffee.com',
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $pemesanan->update(['snap_token' => $snapToken]);

        session()->forget(['cart']);
        session(['order_id' => $kodePemesanan]);

        return view('ordering.payment', compact('snapToken', 'total', 'kodePemesanan'));
    }

    public function updateStatus(Request $request)
    {
        $orderId = $request->order_id;
        $status  = $request->transaction_status;
        $type    = $request->payment_type;

        $pemesanan = Pemesanan::where('order_id', $orderId)->first();

        if ($pemesanan) {
            $selesai = in_array($status, ['settlement', 'capture']);

            $pemesanan->update([
                'transaction_status' => $status,
                'payment_type'       => $type,
                'status'             => $selesai ? 'selesai' : 'pending',
            ]);

            // Trigger jurnal hanya kalau selesai & belum dibuat
            if ($selesai && !$pemesanan->jurnal_dibuat) {
                JurnalService::jurnalPenjualan($pemesanan);
                $pemesanan->update(['jurnal_dibuat' => true]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function success(Request $request)
    {
        $orderId = session('order_id');

        if ($orderId) {
            \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            try {
                $status    = \Midtrans\Transaction::status($orderId);
                $pemesanan = Pemesanan::where('order_id', $orderId)->first();

                if ($pemesanan) {
                    $selesai = in_array(
                        $status->transaction_status,
                        ['settlement', 'capture']
                    );

                    $pemesanan->update([
                        'transaction_status' => $status->transaction_status,
                        'payment_type'       => $status->payment_type,
                        'status'             => $selesai ? 'selesai' : 'pending',
                    ]);

                    // Trigger jurnal hanya kalau selesai & belum dibuat
                    if ($selesai && !$pemesanan->jurnal_dibuat) {
                        JurnalService::jurnalPenjualan($pemesanan);
                        $pemesanan->update(['jurnal_dibuat' => true]);
                    }
                }
            } catch (\Exception $e) {
                // Kalau gagal cek, biarkan pending
            }
        }

        session()->forget(['nama_pemesan', 'no_meja', 'no_wa', 'email', 'order_id']);

        return view('ordering.success');
    }
}
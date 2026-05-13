<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Services\JurnalService;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class KasirController extends Controller
{
    public function index()
    {
        $menus     = Menu::all()->groupBy('kategori');
        $pelanggans = DB::table('pelanggan')->get();

        return view('kasir.index', compact('menus', 'pelanggans'));
    }

    public function addToCart(Request $request)
    {
        $cart = session()->get('kasir_cart', []);
        $id   = $request->id_menu;

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'id_menu'   => $id,
                'nama_menu' => $request->nama_menu,
                'harga'     => $request->harga,
                'qty'       => 1,
            ];
        }

        session()->put('kasir_cart', $cart);

        $total = collect($cart)->sum(fn($i) => $i['harga'] * $i['qty']);

        return response()->json([
            'success' => true,
            'cart'    => $cart,
            'total'   => $total,
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('kasir_cart', []);
        $id   = $request->id_menu;

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] > 1) {
                $cart[$id]['qty']--;
            } else {
                unset($cart[$id]);
            }
        }

        session()->put('kasir_cart', $cart);

        $total = collect($cart)->sum(fn($i) => $i['harga'] * $i['qty']);

        return response()->json([
            'success' => true,
            'cart'    => $cart,
            'total'   => $total,
        ]);
    }

    public function checkout(Request $request)
{
    // ← Ambil dari request, bukan session
    $items = $request->input('items', []);

    if (empty($items)) {
        return response()->json(['error' => 'Keranjang kosong'], 400);
    }

    $total         = collect($items)->sum(fn($i) => $i['harga'] * $i['qty']);
    $kodePemesanan = 'ORD-' . now()->format('YmdHis');

    // Simpan pemesanan
    $pemesanan = Pemesanan::create([
        'kode_pemesanan' => $kodePemesanan,
        'nama_pemesan'   => $request->nama_pelanggan,
        'no_meja'        => $request->meja,
        'sumber'         => 'kasir',
        'total_harga'    => $total,
        'status'         => 'pending',
        'catatan'        => $request->catatan,
    ]);

    // Simpan detail pemesanan
    foreach ($items as $item) {
        DetailPemesanan::create([
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'id_menu'      => $item['id'],
            'nama_menu'    => $item['nama'],
            'harga_satuan' => $item['harga'],
            'qty'          => $item['qty'],
            'subtotal'     => $item['harga'] * $item['qty'],
        ]);
    }

    // Setup Midtrans
    Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    Config::$isSanitized  = true;
    Config::$is3ds        = true;

    $params = [
        'transaction_details' => [
            'order_id'     => $kodePemesanan,
            'gross_amount' => (int) $total,
        ],
        'customer_details' => [
            'first_name' => $request->nama_pelanggan,
        ],
        'item_details' => collect($items)->map(fn($item) => [
            'id'       => $item['id'],
            'price'    => (int) $item['harga'],
            'quantity' => (int) $item['qty'],
            'name'     => substr($item['nama'], 0, 50), // Midtrans max 50 char
        ])->values()->toArray(),
    ];

    $snapToken = Snap::getSnapToken($params);

    session([
        'kasir_snap_token'   => $snapToken,
        'kasir_pemesanan_id' => $pemesanan->id_pemesanan,
    ]);

    return response()->json([
        'success'    => true,
        'snap_token' => $snapToken,
    ]);
}

    public function paymentSuccess(Request $request)
{
    $pemesananId = session('kasir_pemesanan_id');

    if ($pemesananId) {
        $pemesanan = Pemesanan::find($pemesananId);

        if ($pemesanan && !$pemesanan->jurnal_dibuat) {
            $pemesanan->update(['status' => 'selesai']);

            // Trigger jurnal otomatis
            JurnalService::jurnalPenjualan($pemesanan);
            $pemesanan->update(['jurnal_dibuat' => true]);
        }
    }

    session()->forget(['kasir_cart', 'kasir_snap_token', 'kasir_pemesanan_id']);

    return response()->json(['success' => true]);
}
}
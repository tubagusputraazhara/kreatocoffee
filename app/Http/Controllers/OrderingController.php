<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class OrderingController extends Controller
{
    // Halaman form awal
    public function index()
    {
        // Daftar meja untuk dropdown (hardcode dulu, bisa diubah nanti)
        $daftarMeja = [
            'Meja 1', 'Meja 2', 'Meja 3', 'Meja 4', 'Meja 5',
            'Meja 6', 'Meja 7', 'Meja 8', 'Meja 9', 'Meja 10',
        ];

        return view('ordering.index', compact('daftarMeja'));
    }

    // Simpan info pemesan ke session
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

    // Halaman pilih menu
    public function menu()
    {
        if (!session('nama_pemesan')) {
            return redirect()->route('order.index');
        }

        $menus = Menu::where('status_menu', 'Tersedia')->get()->groupBy('kategori_menu');
        $cart  = session('cart', []);

        return view('ordering.menu', compact('menus', 'cart'));
    }

    // Tambah ke keranjang (AJAX)
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

    // Kurangi/hapus dari keranjang (AJAX)
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

    // Update qty langsung
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

    // Halaman checkout
    public function checkout()
    {
        if (!session('nama_pemesan') || empty(session('cart'))) {
            return redirect()->route('order.index');
        }

        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return view('ordering.checkout', compact('cart', 'total'));
    }

    // Proses pembayaran Midtrans
    public function payment(Request $request)
    {
        // Akan diisi di step Midtrans
    }

    // Halaman sukses
    public function success()
    {
        session()->forget(['cart', 'nama_pemesan', 'no_meja', 'no_wa', 'email']);
        return view('ordering.success');
    }
}
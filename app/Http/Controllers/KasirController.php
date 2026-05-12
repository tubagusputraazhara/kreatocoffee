<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB; // Tambahkan ini agar bisa ambil data tanpa Model

class KasirController extends Controller
{
    public function index()
    {
        // 1. Ambil data menu
        $menus = Menu::all()->groupBy('kategori');

        // 2. Ambil data pelanggan langsung dari tabel database
        // Ganti 'pelanggan' dengan nama tabel kamu jika berbeda
        $pelanggans = DB::table('pelanggan')->get(); 

        // 3. Kirim ke view
        return view('kasir.index', compact('menus', 'pelanggans'));
    }

    public function addToCart(Request $request)
    {
        $cart = session()->get('kasir_cart', []);
        $id = $request->id_menu;

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

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('kasir_cart', []);
        $id = $request->id_menu;

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] > 1) {
                $cart[$id]['qty']--;
            } else {
                unset($cart[$id]);
            }
        }

        session()->put('kasir_cart', $cart);

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    public function checkout(Request $request)
    {
        // Logika checkout
    }
}
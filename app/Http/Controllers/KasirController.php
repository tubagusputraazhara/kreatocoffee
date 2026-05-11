<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class KasirController extends Controller
{
    public function index()
    {
        $menus = Menu::all()->groupBy('kategori');

        return view('kasir.index', compact('menus'));
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
        //
    }
}

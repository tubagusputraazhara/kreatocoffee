<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Association\Apriori;
use App\Models\Menu;

class RekomendasiController extends Controller
{
    public function getRekomendasi(Request $request)
    {
        $cartItems = $request->input('items', []);

        if (empty($cartItems)) {
            return response()->json([]);
        }

        // ✅ Ambil histori transaksi
        $transaksi = DB::table('detail_pemesanan')
            ->join('pemesanan', 'detail_pemesanan.id_pemesanan', '=', 'pemesanan.id_pemesanan')
            ->where('pemesanan.status', 'lunas')
            ->select('detail_pemesanan.id_pemesanan', 'detail_pemesanan.nama_menu')
            ->get();

        // ✅ Bentuk dataset
        $dataset = [];
        foreach ($transaksi as $t) {
            $dataset[$t->id_pemesanan][] = $t->nama_menu;
        }
        $dataset = array_values($dataset);

        if (empty($dataset)) {
            return response()->json([]);
        }

        // ✅ Jalankan Apriori
        $associator = new Apriori(0.1, 0.3);
        $associator->train($dataset, []);
        $rules = $associator->getRules();

        // ✅ Cari rekomendasi berdasarkan item di cart
        $rekomendasi = [];
        foreach ($rules as $rule) {
            $antecedent = $rule['antecedent'];

            // Kalau antecedent ada di cart
            foreach ($antecedent as $item) {
                if (in_array($item, $cartItems)) {
                    foreach ($rule['consequent'] as $rec) {
                        // Jangan rekomendasikan yang sudah ada di cart
                        if (!in_array($rec, $cartItems)) {
                            $menu = Menu::where('nama_menu', $rec)->first();
                            if ($menu) {
                                $rekomendasi[$rec] = [
                                    'nama_menu'  => $rec,
                                    'harga'      => $menu->harga,
                                    'id_menu'    => $menu->id_menu,
                                    'confidence' => round($rule['confidence'] * 100) . '%',
                                ];
                            }
                        }
                    }
                }
            }
        }

        return response()->json(array_values($rekomendasi));
    }
}
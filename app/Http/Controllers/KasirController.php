<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    // <<< TAMBAHAN: Logika untuk satu URL /kasir
    public function index()
    {
        if (!Auth::check()) {
            return view('login'); // Menampilkan login
        }

        $pelanggans = Pelanggan::all();
        $menus = Menu::all()->groupBy('kategori');
        return view('kasir.index', compact('pelanggans', 'menus'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'total_harga' => 'required|numeric',
            'items'       => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $pemesanan = new Pemesanan();
            $pemesanan->kode_pemesanan = Pemesanan::generateKode();
            $pemesanan->nama_pemesan   = $request->nama_pemesan;
            $pemesanan->no_meja        = $request->no_meja;
            $pemesanan->catatan        = $request->catatan;
            $pemesanan->total_harga    = $request->total_harga;
            $pemesanan->status         = '1';
            $pemesanan->save();

            foreach ($request->items as $item) {
                $detail = new DetailPemesanan();
                $detail->id_pemesanan = $pemesanan->id_pemesanan;
                $detail->id_menu      = $item['id'];
                $detail->nama_menu    = $item['nama'];
                $detail->harga_satuan = $item['harga'];
                $detail->qty          = $item['qty'];
                $detail->subtotal     = $item['harga'] * $item['qty'];
                $detail->save();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Transaksi berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Error Database: ' . $e->getMessage()], 500);
        }
    }
}
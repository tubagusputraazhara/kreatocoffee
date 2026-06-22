<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\JurnalService;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class KasirController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('login'); // Menampilkan login
        }

        $pelanggans = Pelanggan::all();
        $menus      = Menu::all()->groupBy('kategori');

        return view('kasir.index', compact('pelanggans', 'menus'));
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
            unset($cart[$id]);
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
        $items = $request->input('items', []);

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 400);
        }

        if (empty($request->nama_pelanggan)) {
            return response()->json(['success' => false, 'message' => 'Pelanggan belum dipilih'], 422);
        }

        if (empty($request->meja)) {
            return response()->json(['success' => false, 'message' => 'Meja belum dipilih'], 422);
        }

        $metode = $request->input('metode', 'qris');

        // Catatan metode pembayaran ikut disisipkan di kolom `catatan`,
        // karena tabel pemesanan saat ini belum punya kolom metode_pembayaran tersendiri.
        $labelMetode  = $metode === 'cash' ? 'CASH' : 'QRIS';
        $catatanFinal = trim('[' . $labelMetode . '] ' . ($request->catatan ?? ''));

        try {
            $pemesanan = DB::transaction(function () use ($request, $items, $metode, $catatanFinal) {
                $kodePemesanan = Pemesanan::generateKode();
                $total         = collect($items)->sum(fn($i) => $i['harga'] * $i['qty']);

                $pemesanan = Pemesanan::create([
                    'kode_pemesanan' => $kodePemesanan,
                    'nama_pemesan'   => $request->nama_pelanggan,
                    'no_meja'        => $request->meja,
                    'sumber'         => 'kasir',
                    'total_harga'    => $total,
                    'status'         => $metode === 'cash' ? 'selesai' : 'pending',
                    'catatan'        => $catatanFinal,
                ]);

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

                return $pemesanan;
            });
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage(),
            ], 500);
        }

        // Pembayaran cash: uang sudah diterima langsung di tempat
        if ($metode === 'cash') {
            $this->buatJurnalAman($pemesanan);

            session()->forget('kasir_cart');

            return response()->json([
                'success'      => true,
                'metode'       => 'cash',
                'pemesanan_id' => $pemesanan->id_pemesanan,
            ]);
        }

        // Pembayaran QRIS lewat Midtrans Snap
        try {
            Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $pemesanan->kode_pemesanan,
                    'gross_amount' => (int) $pemesanan->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $pemesanan->nama_pemesan,
                ],
                'item_details' => collect($items)->map(fn($item) => [
                    'id'       => $item['id'],
                    'price'    => (int) $item['harga'],
                    'quantity' => (int) $item['qty'],
                    'name'     => substr($item['nama'], 0, 50), // Midtrans max 50 char
                ])->values()->toArray(),
            ];

            $snapToken = Snap::getSnapToken($params);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QRIS: ' . $e->getMessage(),
            ], 500);
        }

        session([
            'kasir_snap_token'   => $snapToken,
            'kasir_pemesanan_id' => $pemesanan->id_pemesanan,
        ]);

        return response()->json([
            'success'    => true,
            'metode'     => 'qris',
            'snap_token' => $snapToken,
            'order_id'   => $pemesanan->kode_pemesanan,
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $pemesananId = session('kasir_pemesanan_id');
        $orderId     = $request->order_id;

        $pemesanan = $pemesananId
            ? Pemesanan::find($pemesananId)
            : Pemesanan::where('kode_pemesanan', $orderId)->first();

        if ($pemesanan) {
            $pemesanan->update(['status' => 'selesai']);
            $this->buatJurnalAman($pemesanan);
        }

        session()->forget(['kasir_cart', 'kasir_snap_token', 'kasir_pemesanan_id']);

        return response()->json(['success' => true]);
    }

    /**
     * Menerima callback notifikasi dari Midtrans (server-to-server).
     * Route: POST /midtrans/callback
     */
    public function midtransCallback(Request $request)
    {
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $notif = new Notification();

        $orderId            = $notif->order_id;
        $transactionStatus  = $notif->transaction_status;
        $fraudStatus        = $notif->fraud_status;

        $pemesanan = Pemesanan::where('kode_pemesanan', $orderId)->first();

        if (!$pemesanan) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                $pemesanan->update(['status' => 'selesai']);
                $this->buatJurnalAman($pemesanan);
            }
        } elseif ($transactionStatus === 'pending') {
            $pemesanan->update(['status' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $pemesanan->update(['status' => 'batal']);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Panggil jurnal otomatis tanpa membuat checkout gagal kalau service ini error.
     */
    private function buatJurnalAman(Pemesanan $pemesanan): void
    {
        try {
            JurnalService::jurnalPenjualan($pemesanan);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
//biar bagus
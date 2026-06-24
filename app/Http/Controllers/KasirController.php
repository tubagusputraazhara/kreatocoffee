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
use Midtrans\CoreApi;       // ✅ Tambahan dari teman: untuk QRIS via Core API
use Midtrans\Snap;
use Midtrans\Transaction;   // ✅ Tambahan dari teman: untuk polling status
use Midtrans\Notification;

class KasirController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('login');
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

    // ✅ Tambahan dari teman: hapus item dari cart
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

        // ✅ Tambahan dari teman: match expression untuk label metode (lebih clean, + support debit)
        $labelMetode = match ($metode) {
            'cash'  => 'CASH',
            'debit' => 'DEBIT/KARTU',
            default => 'QRIS',
        };
        $catatanFinal = trim('[' . $labelMetode . '] ' . ($request->catatan ?? ''));

        try {
            $pemesanan = DB::transaction(function () use ($request, $items, $metode, $catatanFinal) {
                $kodePemesanan = 'ORD-' . now()->format('YmdHis');
                $total         = collect($items)->sum(fn($i) => $i['harga'] * $i['qty']);

                $pemesanan = Pemesanan::create([
                    'kode_pemesanan' => $kodePemesanan,
                    'nama_pemesan'   => $request->nama_pelanggan,
                    'no_meja'        => $request->meja,
                    'sumber'         => 'kasir',
                    'total_harga'    => $total,
                    // ✅ Nilai status versi main dipertahankan (lunas/belum_lunas)
                    'status'         => $metode === 'cash' ? 'lunas' : 'belum_lunas',
                    'status_pesanan' => 'diproses',
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

        // ✅ Pembayaran cash langsung
        if ($metode === 'cash') {
            $this->buatJurnalAman($pemesanan);
            session()->forget('kasir_cart');

            return response()->json([
                'success'      => true,
                'metode'       => 'cash',
                'pemesanan_id' => $pemesanan->id_pemesanan,
            ]);
        }

        // ✅ Tambahan dari teman: pembayaran Debit/Kartu via Midtrans Snap
        if ($metode === 'debit') {
            try {
                $this->setupMidtrans();

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
                        'name'     => substr($item['nama'], 0, 50),
                    ])->values()->toArray(),
                ];

                $snapToken = Snap::getSnapToken($params);
            } catch (\Throwable $e) {
                report($e);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat transaksi Snap: ' . $e->getMessage(),
                ], 500);
            }

            session(['kasir_pemesanan_id' => $pemesanan->id_pemesanan]);

            return response()->json([
                'success'    => true,
                'metode'     => 'debit',
                'order_id'   => $pemesanan->kode_pemesanan,
                'snap_token' => $snapToken,
            ]);
        }

        // ✅ Pembayaran QRIS via Midtrans Core API (tambahan dari teman — QR langsung tanpa popup Snap)
        try {
            $this->setupMidtrans();

            $params = [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id'     => $pemesanan->kode_pemesanan,
                    'gross_amount' => (int) $pemesanan->total_harga,
                ],
                'item_details' => collect($items)->map(fn($item) => [
                    'id'       => $item['id'],
                    'price'    => (int) $item['harga'],
                    'quantity' => (int) $item['qty'],
                    'name'     => substr($item['nama'], 0, 50),
                ])->values()->toArray(),
                'customer_details' => [
                    'first_name' => $pemesanan->nama_pemesan,
                ],
            ];

            $charge = CoreApi::charge($params);

            // Normalisasi respons ke array supaya aman diakses
            $chargeArr = is_array($charge) ? $charge : json_decode(json_encode($charge), true);

            $qrUrl = null;
            foreach (($chargeArr['actions'] ?? []) as $action) {
                if (($action['name'] ?? null) === 'generate-qr-code') {
                    $qrUrl = $action['url'];
                    break;
                }
            }

            if (!$qrUrl) {
                throw new \Exception('URL QRIS tidak ditemukan pada respons Midtrans.');
            }
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QRIS: ' . $e->getMessage(),
            ], 500);
        }

        session([
            'kasir_snap_token'   => null,
            'kasir_pemesanan_id' => $pemesanan->id_pemesanan,
        ]);

        return response()->json([
            'success'  => true,
            'metode'   => 'qris',
            'order_id' => $pemesanan->kode_pemesanan,
            'qr_url'   => $qrUrl,
            'total'    => (int) $pemesanan->total_harga,
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $pemesananId = session('kasir_pemesanan_id');
        $orderId     = $request->order_id;

        $pemesanan = $pemesananId
            ? Pemesanan::find($pemesananId)
            : Pemesanan::where('kode_pemesanan', $orderId)->first();

        if ($pemesanan && !$pemesanan->jurnal_dibuat) {
            // ✅ Nilai status versi main dipertahankan
            $pemesanan->update(['status' => 'lunas']);
            $this->buatJurnalAman($pemesanan);
            $pemesanan->update(['jurnal_dibuat' => true]);
        }

        session()->forget(['kasir_cart', 'kasir_snap_token', 'kasir_pemesanan_id']);

        return response()->json(['success' => true]);
    }

    /**
     * ✅ Tambahan dari teman: polling status pembayaran QRIS.
     * Route: GET /kasir/check-status/{orderId}
     */
    public function checkStatus(string $orderId)
    {
        try {
            $this->setupMidtrans();

            $status = Transaction::status($orderId);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek status: ' . $e->getMessage(),
            ], 500);
        }

        $statusArr         = is_array($status) ? $status : json_decode(json_encode($status), true);
        $transactionStatus = $statusArr['transaction_status'] ?? null;
        $fraudStatus       = $statusArr['fraud_status'] ?? null;

        $pemesanan  = Pemesanan::where('kode_pemesanan', $orderId)->first();
        $sudahLunas = false;

        if ($pemesanan && in_array($transactionStatus, ['capture', 'settlement'])) {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                // ✅ Nilai status versi main dipertahankan
                if ($pemesanan->status !== 'lunas') {
                    $pemesanan->update(['status' => 'lunas']);
                    $this->buatJurnalAman($pemesanan);
                }
                $sudahLunas = true;
            }
        } elseif ($pemesanan && in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $pemesanan->update(['status' => 'batal']);
        }

        return response()->json([
            'success'            => true,
            'transaction_status' => $transactionStatus,
            'lunas'              => $sudahLunas,
        ]);
    }

    public function midtransCallback(Request $request)
    {
        // ✅ Pakai setupMidtrans() (refactor dari teman)
        $this->setupMidtrans();

        $notif = new Notification();

        $orderId           = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $fraudStatus       = $notif->fraud_status;

        $pemesanan = Pemesanan::where('kode_pemesanan', $orderId)->first();

        if (!$pemesanan) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                // ✅ Nilai status versi main dipertahankan
                $pemesanan->update(['status' => 'lunas']);
                $this->buatJurnalAman($pemesanan);
            }
        } elseif ($transactionStatus === 'pending') {
            // ✅ Nilai status versi main dipertahankan
            $pemesanan->update(['status' => 'belum_lunas']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $pemesanan->update(['status' => 'batal']);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * ✅ Tambahan dari teman: ekstrak konfigurasi Midtrans ke method tersendiri.
     */
    private function setupMidtrans(): void
    {
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    private function buatJurnalAman(Pemesanan $pemesanan): void
    {
        try {
            JurnalService::jurnalPenjualan($pemesanan);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/kasir'); // Diredirect ke /kasir, logika KasirController menangani sisanya
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/kasir');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNGSI TAMBAHAN: PROSES QRIS DINAMIS VIA MIDTRANS SANDBOX (KODE ASLI ANDA)
    |--------------------------------------------------------------------------
    */
    public function prosesQris(Request $request)
    {
        $request->validate([
            'total_harga' => 'required|numeric|min:1000',
        ]);

        $nominal = $request->total_harga;
        $orderId = 'KREATO-' . time(); 
        $serverKey = env('MIDTRANS_SERVER_KEY');

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
            ])->post('https://api.sandbox.midtrans.com/v2/charge', [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $nominal, 
                ],
                'qris' => [
                    'acquirer' => 'gopay'
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                $qrString = null;
                if (isset($result['actions'])) {
                    foreach ($result['actions'] as $action) {
                        if ($action['name'] === 'generate-qr-code') {
                            $qrString = $action['url'];
                            break;
                        }
                    }
                }

                if (!$qrString) {
                    $qrString = $result['qr_string'] ?? '00020101021226640014ID1234567890123';
                }

                return response()->json([
                    'status' => 'success',
                    'order_id' => $orderId,
                    'qr_string' => $qrString,
                ]);
            }

            $errorResponse = $response->json();
            return response()->json([
                'status' => 'error',
                'message' => $errorResponse['status_message'] ?? 'Gagal memproses pembayaran ke Midtrans.'
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan jaringan: ' . $e->getMessage()
            ], 500);
        }
    }
}
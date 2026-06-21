<?php

use App\Http\Controllers\OrderingController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurnalExportController;
use App\Http\Controllers\PemesananExportController;
use App\Http\Controllers\SupplierController;

// =========================================================================
// ROUTE UTAMA (http://127.0.0.1:8000/) - LANGSUNG OPER KE KASIR
// =========================================================================
Route::get('/', function () {
    return redirect('/kasir');
});

// ROUTE AUTH UNTUK LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================
// CUSTOMER QR ORDERING
// =========================
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/', [OrderingController::class, 'index'])->name('index');
    Route::post('/store-info', [OrderingController::class, 'storeInfo'])->name('storeInfo');
    Route::get('/menu', [OrderingController::class, 'menu'])->name('menu');
    Route::post('/add-to-cart', [OrderingController::class, 'addToCart'])->name('addToCart');
    Route::post('/remove-from-cart', [OrderingController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/update-cart', [OrderingController::class, 'updateCart'])->name('updateCart');
    Route::get('/checkout', [OrderingController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [OrderingController::class, 'payment'])->name('payment');
    Route::post('/update-status', [OrderingController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/success', [OrderingController::class, 'success'])->name('success');
});

// =========================================================================
// KASIR POS
// =========================================================================
Route::prefix('kasir')->name('kasir.')->group(function () {

    // Alamat utama kasir
    Route::get('/', [KasirController::class, 'index'])->name('index');

    // Proses submit form login (action form mengarah ke rute kasir)
    Route::post('/login-proses', [AuthController::class, 'login'])->name('login-proses');

    Route::post('/simpan-pesanan', [PesananController::class, 'simpan'])->name('simpan');
    Route::get('/detail/{id}', [PesananController::class, 'showDetail'])->name('detail');

    Route::post('/add-to-cart', [KasirController::class, 'addToCart'])->name('addToCart');
    Route::post('/remove-from-cart', [KasirController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [KasirController::class, 'payment'])->name('payment');
    Route::post('/payment-success', [KasirController::class, 'paymentSuccess'])->name('paymentSuccess');

    Route::post('/proses-qris', [AuthController::class, 'prosesQris'])->name('prosesQris');
});
// =========================
// KASIR POS
// =========================
Route::prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [KasirController::class, 'index'])->name('index');
    Route::post('/add-to-cart', [KasirController::class, 'addToCart'])->name('addToCart');
    Route::post('/remove-from-cart', [KasirController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
    Route::post('/payment-success', [KasirController::class, 'paymentSuccess'])->name('paymentSuccess');
});

// =========================
// MIDTRANS CALLBACK
// =========================
Route::post('/midtrans/callback', [KasirController::class, 'midtransCallback'])
    ->name('midtrans.callback');

// =========================
// EXPORT PDF
// =========================
Route::get('/jurnal/export/pdf', [JurnalExportController::class, 'exportPdf'])
    ->name('jurnal.export.pdf')
    ->middleware('auth');

Route::get('/pemesanan/export/pdf', [PemesananExportController::class, 'exportPdf'])
    ->name('pemesanan.export.pdf')
    ->middleware('auth');

Route::resource('suppliers', SupplierController::class);

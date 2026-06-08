<?php

use App\Http\Controllers\OrderingController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PesananController; 
use App\Http\Controllers\AuthController; // <<< PERUBAHAN 1: Menghubungkan AuthController agar dikenali rute

// =========================================================================
// ROUTE AUTENTIKASI KASIR (PERSIS SESUAI FOTO POTONGAN MODUL YANG KAMU KIRIM)
// =========================================================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // <<< PERUBAHAN 2: Menambahkan ->name('login') agar middleware tahu kemana harus melempar user
Route::post('/login', [AuthController::class, 'login']);         
Route::post('/logout', [AuthController::class, 'logout']);       


// =========================
// CUSTOMER QR ORDERING
// =========================
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/', [OrderingController::class, 'index'])->name('index');
    Route::post('/store-info', [OrderingController::class, 'storeInfo'])->name('storeInfo');
    Route::get('/menu', [OrderingController::class, 'menu'])->name('menu');
    Route::post('/add-to-cart', [OrderingController::class, 'addToCart'])->name('addToCart');
    Route::post('/remove-from-cart', [OrderingController::class, 'removeFromCart'])->name('removeFromCart');
    Route::get('/checkout', [OrderingController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [OrderingController::class, 'payment'])->name('payment');
    Route::get('/success', [OrderingController::class, 'success'])->name('success');
});


// =========================================================================
// KASIR POS (SUDAH DIKUNCI OLEH MIDDLEWARE AUTH)
// =========================================================================
// Menambahkan ->middleware('auth') agar rute kasir otomatis mendeteksi status login
Route::prefix('kasir')->name('kasir.')->middleware('auth')->group(function () { // <<< PERUBAHAN 3: Kunci rute kasir
    Route::get('/', [KasirController::class, 'index'])->name('index');
    
    Route::post('/simpan-pesanan', [PesananController::class, 'simpan'])->name('simpan');
    Route::get('/detail/{id}', [PesananController::class, 'showDetail'])->name('detail');

    Route::post('/add-to-cart', [KasirController::class, 'addToCart'])->name('addToCart');
    Route::post('/remove-from-cart', [KasirController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [KasirController::class, 'payment'])->name('payment');
});


// =========================
// MIDTRANS CALLBACK
// =========================
Route::post('/midtrans/callback', [PesananController::class, 'callback'])->name('midtrans.callback');

Route::get('/jurnal/export/pdf', [App\Http\Controllers\JurnalExportController::class, 'exportPdf'])
    ->name('jurnal.export.pdf')
    ->middleware('auth');
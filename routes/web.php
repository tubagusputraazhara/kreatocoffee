<?php

use App\Http\Controllers\OrderingController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\JurnalExportController;
use App\Http\Controllers\PemesananExportController;

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
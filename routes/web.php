<?php
use App\Http\Controllers\OrderingController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PesananController; // Pastikan Controller ini sudah dibuat

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
    
    // Alur Baru: Simpan Pesanan & Redirect ke Detail
    Route::post('/simpan-pesanan', [PesananController::class, 'simpan'])->name('simpan');
    Route::get('/detail/{id}', [PesananController::class, 'showDetail'])->name('detail');

        
    // Alur Baru: Simpan Pesanan & Redirect ke Detail
    Route::post('/simpan-pesanan', [PesananController::class, 'simpan'])->name('simpan');
    Route::get('/detail/{id}', [PesananController::class, 'showDetail'])->name('detail');

    // Route lama (opsional jika masih digunakan)

    Route::post('/add-to-cart', [KasirController::class, 'addToCart'])->name('addToCart');
    Route::post('/remove-from-cart', [KasirController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [KasirController::class, 'payment'])->name('payment');
});
// =========================
// MIDTRANS CALLBACK (Optional but Recommended)
// =========================
// Untuk menangani perubahan status pembayaran otomatis dari server Midtrans
Route::post('/midtrans/callback', [PesananController::class, 'callback'])->name('midtrans.callback');

// =========================
// MIDTRANS CALLBACK (Optional but Recommended)
// =========================
// Untuk menangani perubahan status pembayaran otomatis dari server Midtrans
Route::post('/midtrans/callback', [PesananController::class, 'callback'])->name('midtrans.callback');

Route::get('/jurnal/export/pdf', [App\Http\Controllers\JurnalExportController::class, 'exportPdf'])
    ->name('jurnal.export.pdf')
    ->middleware('auth');

Route::get('/pemesanan/export/pdf', [App\Http\Controllers\PemesananExportController::class, 'exportPdf'])
    ->name('pemesanan.export.pdf')
    ->middleware('auth');
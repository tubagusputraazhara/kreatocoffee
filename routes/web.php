<?php
use App\Http\Controllers\OrderingController;
use App\Http\Controllers\KasirController;

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


// =========================
// KASIR POS
// =========================
Route::prefix('kasir')->name('kasir.')->group(function () {

    Route::get('/', [KasirController::class, 'index'])->name('index');

    Route::post('/add-to-cart', [KasirController::class, 'addToCart'])->name('addToCart');

    Route::post('/remove-from-cart', [KasirController::class, 'removeFromCart'])->name('removeFromCart');

    Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');

    Route::post('/payment', [KasirController::class, 'payment'])->name('payment');
});


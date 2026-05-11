<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//
Route::get('/', function () {
    return view('welcome');
});

// ===== QR Ordering Customer =====
Route::prefix('order')->name('order.')->group(function () {
    // Form awal (nama, meja, WA, email)
    Route::get('/', [App\Http\Controllers\OrderingController::class, 'index'])->name('index');
    Route::post('/store-info', [App\Http\Controllers\OrderingController::class, 'storeInfo'])->name('storeInfo');

    // Halaman pilih menu
    Route::get('/menu', [App\Http\Controllers\OrderingController::class, 'menu'])->name('menu');

    // Keranjang (AJAX)
    Route::post('/add-to-cart', [App\Http\Controllers\OrderingController::class, 'addToCart'])->name('addToCart');
    Route::post('/remove-from-cart', [App\Http\Controllers\OrderingController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/update-cart', [App\Http\Controllers\OrderingController::class, 'updateCart'])->name('updateCart');

    // Checkout & pembayaran
    Route::get('/checkout', [App\Http\Controllers\OrderingController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [App\Http\Controllers\OrderingController::class, 'payment'])->name('payment');
    Route::get('/success', [App\Http\Controllers\OrderingController::class, 'success'])->name('success');
});


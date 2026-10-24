<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FRONT PAGE (publik)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{product}', [HomeController::class, 'show'])->name('product.show');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/berhasil/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

/*
|--------------------------------------------------------------------------
| BACK PAGE (wajib login, auth dari Breeze)
|--------------------------------------------------------------------------
*/
// Breeze mengarahkan user ke route 'dashboard' setelah login
Route::get('/dashboard', fn () => redirect()->route('admin.products.index'))
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', AdminProductController::class)->except(['show']);

    // Produk per kategori (relasi hasMany: Category -> products)
    Route::get('categories/{category}/products', [AdminProductController::class, 'byCategory'])->name('categories.products');

    // Pesanan dari checkout
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
});

require __DIR__ . '/auth.php';

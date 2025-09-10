<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LacakPesananController;
use App\Http\Livewire\LacakPesanan;

// produk
use App\Http\Controllers\TopiController;
use App\Http\Controllers\KaosController;
use App\Http\Controllers\KemejaController;
use App\Http\Controllers\JaketController;
use App\Http\Controllers\HoodieController;
use App\Http\Controllers\TasController;
use App\Http\Controllers\CelanaController;
use App\Http\Controllers\AksesorisController;



Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/cart/bulk-delete', [CartController::class, 'bulkDelete'])->name('cart.bulkDelete');
Route::post('/checkout/multiple', [CheckoutController::class, 'multiple'])->name('checkout.multiple');


Route::get('/', [ProductController::class, 'index'])->name('home');

// all produk
Route::get('/all-product', [ProductController::class, 'allProduct'])->name('product');

Route::post('/upload-bukti', [CheckoutController::class, 'uploadBukti'])->name('upload.bukti');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::post('/checkout/{id}/process', [ProductController::class, 'processPayment'])->name('processPayment');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/{id}', [CheckoutController::class, 'show'])->name('checkout');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');


Route::get('/search-products', [ProductController::class, 'search'])->name('products.search');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/lacak-pesanan', LacakPesanan::class)->name('lacak.index');
require __DIR__.'/auth.php';





// FOOTER
Route::get('/kebijakan-privasi', function () {
    return view('footer.kebijakan-privasi');
});
Route::get('/syarat-ketentuan', function () {
    return view('footer.syarat-ketentuan');
});

Route::get('/tentang-produk', function () {
    return view('footer.tentang-produk');
});
Route::get('/cara-berbelanja', function () {
    return view('footer.cara-berbelanja');
});
Route::get('/pembayaran', function () {
    return view('footer.pembayaran');
});
Route::get('/pengiriman', function () {
    return view('footer.pengiriman');
});
Route::get('/bantuan', function () {
    return view('bantuan');
});

Route::get('/test-api', function () {
    return view('test-page');
});

// produk
Route::get('/topi', [TopiController::class, 'index'])->name('produk.topi');
Route::get('/kaos', [KaosController::class, 'index'])->name('produk.kaos');
Route::get('/kemeja', [KemejaController::class, 'index'])->name('produk.kemeja');
Route::get('/jaket', [JaketController::class, 'index'])->name('produk.jaket');
Route::get('/hoodie', [HoodieController::class, 'index'])->name('produk.hoodie');
Route::get('/tas', [TasController::class, 'index'])->name('produk.tas');
Route::get('/celana', [CelanaController::class, 'index'])->name('produk.celana');
Route::get('/aksesoris', [AksesorisController::class, 'index'])->name('produk.aksesoris');


// Tambahkan rute ini ke file web.php Anda
Route::get('/checkout/get-provinces', [CheckoutController::class, 'getProvinces']);
Route::get('/checkout/get-cities/{province_id}', [CheckoutController::class, 'getCities']);
Route::get('/checkout/get-subdistricts/{city_id}', [CheckoutController::class, 'getSubdistricts']);
Route::post('/checkout/calculate-shipping-cost', [CheckoutController::class, 'calculateShippingCost'])->name('checkout.calculate-shipping-cost');
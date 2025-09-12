<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LacakPesananController;
use App\Http\Livewire\LacakPesanan;

// produk
use App\Http\Controllers\TshirtController;
use App\Http\Controllers\PoloShirtController;
use App\Http\Controllers\JerseyController;
use App\Http\Controllers\ZipperHoodieController;
use App\Http\Controllers\JortspantsController;
use App\Http\Controllers\SweatpantsController;
use App\Http\Controllers\TruckerController;
use App\Http\Controllers\AccessoriesController;
use App\Models\Order;
use Spatie\Browsershot\Browsershot;

Route::get('/test-receipt/{orderId}', function ($orderId) {
    try {
        $order = Order::findOrFail($orderId);
        $receiptHtml = view('receipt', ['order' => $order])->render();
        $fileName = 'receipts/test-' . $order->order_number . '.png';

        Browsershot::html($receiptHtml)
            ->setNodeBinary('/usr/local/bin/node')
            ->setNpmBinary('/usr/local/bin/npm')
            ->setChromePath('/Applications/Google Chrome.app/Contents/MacOS/Google Chrome')
            ->noSandbox()
            ->waitUntilNetworkIdle()
            ->save(storage_path('app/public/' . $fileName));

        return "Resi berhasil dibuat di " . $fileName;

    } catch (\Exception $e) {
        return "Terjadi kesalahan: " . $e->getMessage();
    }
});

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
Route::get('/tshirt', [TshirtController::class, 'index'])->name('produk.tshirt');
Route::get('/poloshirt', [PoloShirtController::class, 'index'])->name('produk.poloshirt');
Route::get('/jersey', [JerseyController::class, 'index'])->name('produk.jersey');
Route::get('/zipperhoodie', [ZipperHoodieController::class, 'index'])->name('produk.zipperhoodie');
Route::get('/jortspants', [JortspantsController::class, 'index'])->name('produk.jortspants');
Route::get('/sweatpants', [SweatpantsController::class, 'index'])->name('produk.sweatpants');
Route::get('/trucker', [TruckerController::class, 'index'])->name('produk.trucker');
Route::get('/accessories', [AccessoriesController::class, 'index'])->name('produk.accessories');


// Tambahkan rute ini ke file web.php Anda
Route::get('/checkout/get-provinces', [CheckoutController::class, 'getProvinces']);
Route::get('/checkout/get-cities/{province_id}', [CheckoutController::class, 'getCities']);
Route::get('/checkout/get-subdistricts/{city_id}', [CheckoutController::class, 'getSubdistricts']);
Route::post('/checkout/calculate-shipping-cost', [CheckoutController::class, 'calculateShippingCost'])->name('checkout.calculate-shipping-cost');
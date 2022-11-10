<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Products;
use App\Http\Controllers\Shops;
use App\Http\Controllers\Users;
use App\Http\Controllers\Sales;
use App\Http\Controllers\Customers;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Rates;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Stocks;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {
    return Inertia::render('Test');
});


Route::get('/billing', function () {
    return Inertia::render('Billing/MakeSale');
});



Route::get('/', function () {
    return redirect('login');
});


Route::get('/dashboard', [Dashboard::class,'index'])->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    /* --- Resource Route --- */
    Route::resource('product',Products::class);
    Route::resource('shop',Shops::class);
    Route::resource('user',Users::class);
    Route::resource('settings',Settings::class);
    Route::resource('rate',Rates::class);
    Route::resource('stocks',Stocks::class);


    /* -- Get Route -- */
    Route::get('filter/product/shops',[Shops::class,'filter_shops_by_product']);
    Route::get('print-receipt',[CartController::class, 'printReceipt'])->name('print-receipt');
    Route::get('stock/view/requests',[Stocks::class,'viewRequests'])->name('stock.view.request');
    Route::get('stock/request/detail/{id}',[Stocks::class,'stock_request_detail'])->name('stock.request.detail');
    Route::get('make-sale',[Sales::class,'make'])->name('make-sale');
    Route::get('fetch/products',[Sales::class,'getProducts'])->name('fetch.products');
    Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
    Route::get('cartTest', [CartController::class, 'cartTest'])->name('cart.test');
    Route::get('filter/product/{id}',[Products::class,'filterProduct'])->name('filter.product');
    Route::get('get/rate',[Rates::class,'getRate'])->name('get.rate');
    Route::get('get/supplier/{supplier}/products',[Users::class,'supplierProducts'])->name('supplier.products.list');
    Route::get('import-data',[Dashboard::class,'import']);
    Route::post('uploadImport',[Dashboard::class,'uploadImport']);


    /* -- Post Route -- */
    Route::post('stock/request/submit',[Stocks::class,'requested'])->name('stock.request.submit');
    Route::post('stock/request/approved/{id}',[Stocks::class,'approved'])->name('approve.stock.approved');
    Route::post('stock/request/payment/options/{id}',[Stocks::class,'payment_options'])->name('stock.request.payment.option');
    Route::post('stock/send/{id}',[Stocks::class,'stock_send'])->name('send.stock');
    Route::post('stock/received/{id}',[Stocks::class,'stock_received'])->name('received.stock');
    Route::post('stock/completed/{id}',[Stocks::class,'stock_completed'])->name('completed.stock');
    Route::post('stock/direct/request',[Stocks::class,'directRequested'])->name('direct.requested');
    Route::post('customer/existance',[Customers::class,'customerExistance'])->name('customer.existance');
    Route::post('customer/store',[Customers::class,'store'])->name('customer.store');
    Route::post('cart', [CartController::class, 'addToCart'])->name('cart.store');
    Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('cart/remove', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::post('cart/clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
    Route::post('cart/checkout',[CartController::class,'checkout'])->name('cart.checkout');
    Route::post('exceptional/rate',[Rates::class,'exceptionalRate'])->name('exceptional.rate');
    Route::post('razorpay/order',[CartController::class,'makeOrder'])->name('razorpay.make.order');
    Route::post('payment/store',[CartController::class,'paymentStore'])->name('payment.store');
    Route::post('update/purchase/history',[Sales::class,'updatePurchaseHistory'])->name('update.purchase.history');
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});


require __DIR__.'/auth.php';

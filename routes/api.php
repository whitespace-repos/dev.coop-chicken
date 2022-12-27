
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Desktop\RestApiHandler;
use App\Http\Controllers\Desktop\SyncDataHandler;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthenticatedSessionController::class)->group(function(){
    Route::post('login','login');
});

Route::middleware('auth:sanctum')->get('/user/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        "success" => true,
        "message" => "User Logout Successfully"
    ]);
});


/**
 *  For Products
 */
Route::middleware('auth:sanctum')->get('/products',[RestApiHandler::class,'getProducts']);


/**
 * For Customers
 */
Route::middleware('auth:sanctum')->get('/customer/{phone}/{detail?}',[RestApiHandler::class,'getCustomerByPhone']);
Route::middleware('auth:sanctum')->post('/customer',[RestApiHandler::class,'saveCustomer']);
Route::middleware('auth:sanctum')->post('/checkout',[RestApiHandler::class,'checkout']);
Route::middleware('auth:sanctum')->post('/save/payment',[RestApiHandler::class,'savePayment']);

Route::middleware('auth:sanctum')->get('/get/last12month/sale',[RestApiHandler::class,'getSaleOfLast12Month']);







// Sync Data to Database
Route::middleware('auth:sanctum')->post('/sync/customers',[SyncDataHandler::class,'customers']);
Route::middleware('auth:sanctum')->post('/sync/sales',[SyncDataHandler::class,'sales']);




//Stock Request
Route::middleware('auth:sanctum')->post('/stock/request',[SyncDataHandler::class,'stockRequest']);
Route::middleware('auth:sanctum')->get('/stock/requests',[SyncDataHandler::class,'getAllSTockRequets']);
Route::middleware('auth:sanctum')->post('/fetch/updated/stock/requests',[SyncDataHandler::class,'fetchUpdatedStockRequest']);
Route::middleware('auth:sanctum')->post('/updated/stock/requests/payments',[SyncDataHandler::class,'updatePaymentForStockRequest']);
Route::middleware('auth:sanctum')->post('/supplier/receive/stock/requests',[SyncDataHandler::class,'receiveStockRequest']);

Route::middleware('auth:sanctum')->post('/sync/stock/requests',[SyncDataHandler::class,'syncStockRequests']);










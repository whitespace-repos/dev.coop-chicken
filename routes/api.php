<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = User::with('shop')->find($request->user()->id);
    return $user;
});

Route::middleware('auth:sanctum')->get('/user/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        "success" => true,
        "message" => "User Logout Successfully"
    ]);
});

Route::middleware('auth:sanctum')->get('/shop', function (Request $request) {
    $success = [
                    "shop" => $request->user()->shop,
                    "products" => $request->user()->shop->products
    ];
    return response()->json([
        "success" => true,
        "data" => $success,
        "message" => "User Logout Successfully"
    ]);
});


Route::controller(AuthenticatedSessionController::class)->group(function(){
    Route::post('login','login');
});
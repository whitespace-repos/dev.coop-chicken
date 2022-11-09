<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Desktop\RestApiHandler;
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

Route::middleware('auth:sanctum')->get('/products',[RestApiHandler::class,'getProducts']);


Route::controller(AuthenticatedSessionController::class)->group(function(){
    Route::post('login','login');
});
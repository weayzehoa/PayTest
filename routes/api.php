<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/', function () {
    return response()->json('test',200);
});

use App\Http\Controllers\API\PayController;
Route::post('/pay/newebpay/return', [PayController::class, 'newebpayReturn']);
Route::post('/pay/newebpay/notify', [PayController::class, 'newebpayNotify']);
Route::resource('pay', PayController::class);

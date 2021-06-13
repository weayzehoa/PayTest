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

use App\Http\Controllers\API\OrderController;
Route::post('newebpay/return', [OrderController::class, 'return']);
Route::post('newebpay/notify', [OrderController::class, 'notify']);
Route::resource('order', OrderController::class);

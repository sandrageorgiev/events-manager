<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CouponController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/coupons')->group(function () {
    Route::get('/', [CouponController::class, 'index']);
    Route::post('/create', [CouponController::class, 'store']);
    Route::get('/{name}', [CouponController::class, 'show']);
    Route::put('/{name}', [CouponController::class, 'update']);
    Route::delete('/remove/{name}', [CouponController::class, 'destroy']);
});

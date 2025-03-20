<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CouponController;
use \App\Http\Controllers\EventController;

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


Route::prefix('/events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::post('/create', [EventController::class, 'store']);
    Route::get('/{id}', [EventController::class, 'show']);
    Route::put('/{id}', [EventController::class, 'update']);
    Route::delete('/{id}/delete', [EventController::class, 'destroy']);
    Route::post('/upload', [EventController::class, 'upload']);
    Route::get('/{id}/images/{num}', [EventController::class, 'getImageByEventId']);
    Route::post('/publish', [EventController::class, 'publishTickets']);
});


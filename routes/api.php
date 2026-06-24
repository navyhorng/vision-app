<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductScanController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/debug', function () {
    return [
        'app_url' => config('app.url'),
        'url' => url('/'),
        'asset' => asset('test.css'),
        'secure' => request()->secure(),
        'scheme' => request()->getScheme(),
    ];
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/upload', [ProductScanController::class, 'upload']);
    Route::post('/scan', [ProductScanController::class, 'scan']);
    Route::get('/result/{id}', [ProductScanController::class, 'result']);
});

// Route::post('/upload', [ProductScanController::class, 'upload']);
// Route::post('/scan', [ProductScanController::class, 'scan']);
// Route::get('/result/{id}', [ProductScanController::class, 'result']);

<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductScanController;


Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
Route::post('/login', function () {
    return response()->json([
        'status' => 'ok'
    ]);
});
Route::any('/debug-post', function () {
    return [
        'method' => request()->method(),
        'input' => request()->all(),
    ];
});
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
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

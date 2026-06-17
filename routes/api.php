<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductScanController;

Route::post('/upload', [ProductScanController::class, 'upload']);
Route::post('/scan', [ProductScanController::class, 'scan']);
Route::get('/result/{id}', [ProductScanController::class, 'result']);

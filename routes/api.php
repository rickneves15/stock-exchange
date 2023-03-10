<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinancialAssetsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('financial-assets', [FinancialAssetsController::class, 'index']);
    Route::get('financial-assets/{symbol}', [FinancialAssetsController::class, 'show']);
    Route::post('financial-assets', [FinancialAssetsController::class, 'store']);
    Route::put('financial-assets/{symbol}', [FinancialAssetsController::class, 'update']);
    Route::delete('financial-assets/{symbol}', [FinancialAssetsController::class, 'destroy']);
});

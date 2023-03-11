<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinancialAssetsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\WalletController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('financial-assets', [FinancialAssetsController::class, 'index']);
    Route::get('financial-assets/{symbol}', [FinancialAssetsController::class, 'show']);
    Route::post('financial-assets', [FinancialAssetsController::class, 'store']);
    Route::put('financial-assets/{symbol}', [FinancialAssetsController::class, 'update']);
    Route::delete('financial-assets/{symbol}', [FinancialAssetsController::class, 'destroy']);

    Route::post('transactions/buy', [TransactionsController::class, 'buy']);
    Route::post('transactions/sell', [TransactionsController::class, 'sell']);

    Route::get('wallet', [WalletController::class, 'index']);
});

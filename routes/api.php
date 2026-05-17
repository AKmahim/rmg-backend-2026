<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\SpinnerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ── Spinner campaign routes (public, no auth required) ──────────────────────
Route::prefix('spinner')->group(function () {
    Route::post('/send-otp',    [SpinnerController::class, 'sendOtp']);
    Route::post('/verify-otp',  [SpinnerController::class, 'verifyOtp']);
    Route::post('/save-score',  [SpinnerController::class, 'saveScore']);
    Route::post('/check-phone', [SpinnerController::class, 'checkPhone']);
});


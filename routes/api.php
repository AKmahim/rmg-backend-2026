<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\SpinnerController;
use App\Http\Controllers\admin\QuizController;

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

// ── Quiz campaign routes (public, no auth required) ───────────────────────────
Route::prefix('quiz')->group(function () {
    Route::post('/send-otp',    [QuizController::class, 'sendOtp']);
    Route::post('/verify-otp',  [QuizController::class, 'verifyOtp']);
    Route::post('/save-score',  [QuizController::class, 'saveScore']);
    Route::post('/check-phone', [QuizController::class, 'checkPhone']);
});


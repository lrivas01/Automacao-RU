<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::apiResource('users', UserController::class);

Route::prefix('users')->group(function () {
    Route::get('users/{id}', [UserController::class, 'show'])->name('user.show');
    Route::post('users/', [UserController::class, 'store'])->name('user.store');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::patch('users/{id}', [UserController::class, 'update'])->name('user.update');
});

// Payment routes
Route::middleware('auth:students')->group(function () {
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments', [PaymentController::class, 'index']);
});

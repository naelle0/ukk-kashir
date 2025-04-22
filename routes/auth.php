<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisterController::class, 'store']);

    Route::get('forgot-password', [ResetPasswordController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [ResetPasswordController::class, 'store'])
        ->name('password.email');

});
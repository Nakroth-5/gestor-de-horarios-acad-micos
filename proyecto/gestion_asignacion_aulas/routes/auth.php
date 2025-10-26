<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Profile\Auth\ConfirmPassword;
use App\Livewire\Profile\Auth\ForgotPassword;
use App\Livewire\Profile\Auth\ResetPassword;
use App\Livewire\Profile\Auth\VerifyEmail;
use Illuminate\Support\Facades\Route;
use App\Livewire\Profile\Auth\Register;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Route::get('register', Register::class)
        ->name('register');

    Volt::route('login', 'profile.auth.login')
        ->name('login');

    Route::get('forgot-password', ForgotPassword::class)->name('password.request');

    Route::get('reset-password/{token}', ResetPassword::class)
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', VerifyEmail::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('confirm-password', ConfirmPassword::class)
        ->name('confirm.password');
});

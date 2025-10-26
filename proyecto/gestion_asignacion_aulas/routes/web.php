<?php

use App\Livewire\UserSecurity\User;
use App\Livewire\UserSecurity\Role;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/user', User::class)->name('user');

Route::get('/role', Role::class)->name('role');

require __DIR__.'/auth.php';

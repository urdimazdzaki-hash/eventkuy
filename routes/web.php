<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Halaman awal -> arahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ===== Auth (tidak perlu login untuk akses) =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ===== Event (wajib login) =====
Route::middleware('auth')->group(function () {
    Route::resource('events', EventController::class);
});
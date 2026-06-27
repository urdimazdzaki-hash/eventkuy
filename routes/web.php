<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RundownController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChecklistController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('/theme-toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('events', EventController::class);
    Route::get('/rundowns', [RundownController::class, 'index'])->name('rundowns.index');
    Route::post('/events/{event}/checklists', [ChecklistController::class, 'store'])->name('checklists.store');
   Route::patch('/events/{event}/checklists/{checklist}/toggle', [ChecklistController::class, 'toggle'])->name('checklists.toggle');
   Route::delete('/events/{event}/checklists/{checklist}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');
});
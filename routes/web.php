<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RundownController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

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

    // Event (Mahasiswa 1)
    Route::resource('events', EventController::class);

    // Vendor & Venue (Mahasiswa 2)
    Route::resource('vendors', VendorController::class);
    Route::resource('venues', VenueController::class);

    // Cuaca (Mahasiswa 3)
    Route::get('/cuaca', [WeatherController::class, 'index'])->name('cuaca.index');
    Route::get('/test-cuaca/{city}', function ($city) {
    $controller = new \App\Http\Controllers\WeatherController();
    return $controller->getRainProbabilityNext3Days($city);
});

    // Rundown
    Route::get('/rundowns', [RundownController::class, 'index'])->name('rundowns.index');

    // Checklist
    Route::post('/events/{event}/checlists', [ChecklistController::class, 'store'])->name('checklists.store');
    Route::patch('/events/{event}/checklists/{checklist}/toggle', [ChecklistController::class, 'toggle'])->name('checklists.toggle');
    Route::delete('/events/{event}/checklists/{checklist}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');

    // Payment
    Route::get('/events/{event}/payment', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/events/{event}/payment', [PaymentController::class, 'confirm'])->name('payment.confirm');
});
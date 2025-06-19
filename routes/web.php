<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Web authentication routes (untuk Inertia)
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'webLogin']);
Route::post('logout', [AuthController::class, 'webLogout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__ . '/settings.php';
// Jangan include auth.php karena kita buat custom routes
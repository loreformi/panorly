<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('settings/theme')->name('theme.')->group(function () {
        Route::get('/', [ThemeController::class, 'edit'])->name('edit');
        Route::put('/', [ThemeController::class, 'update'])->name('update');
        Route::post('/background', [ThemeController::class, 'uploadBackground'])->name('background.upload');
        Route::get('/export', [ThemeController::class, 'export'])->name('export');
        Route::post('/import', [ThemeController::class, 'import'])->name('import');
    });
});

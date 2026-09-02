<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:6,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::prefix('apps')->name('apps.')->group(function () {
        Route::post('/', [AppController::class, 'store'])->name('store');
        Route::put('/{app}', [AppController::class, 'update'])->name('update');
        Route::post('/{app}/duplicate', [AppController::class, 'duplicate'])->name('duplicate');
        Route::delete('/{app}', [AppController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [AppController::class, 'reorder'])->name('reorder');
    });
    Route::prefix('settings/theme')->name('theme.')->group(function () {
        Route::get('/', [ThemeController::class, 'edit'])->name('edit');
        Route::put('/', [ThemeController::class, 'update'])->name('update');
        Route::post('/background', [ThemeController::class, 'uploadBackground'])->name('background.upload');
        Route::get('/export', [ThemeController::class, 'export'])->name('export');
        Route::post('/import', [ThemeController::class, 'import'])->name('import');
    });
});

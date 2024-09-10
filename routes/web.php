<?php

use App\Actions\Server\TDIConnection;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SynchronizationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'index')->name('login');
        Route::get('connect/{token}', 'store')->name('connect');
    });
});

Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::resource('synchronizations', SynchronizationController::class)->only(['index', 'store']);

    Route::resource('attendance', AttendanceController::class)->only(['index', 'store']);

    Route::get('user/profile', fn () => TDIConnection::redirect('user/profile'))->name('profile');
});

// Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

// require __DIR__.'/auth.php';

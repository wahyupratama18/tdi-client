<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SynchronizationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'index')->name('login');
        Route::get('/connect/{token}', 'store')->name('connect');
    });
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::resource('synchronizations', SynchronizationController::class)->only(['index', 'store', 'update']);

    Route::resource('attendance', AttendanceController::class)->only(['index', 'store']);
});

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/lupa_password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboard_admin.index');
    })->name('admin.dashboard');

    Route::get('/peserta/dashboard', function () {
        return view('dashboard_peserta.index');
    })->name('peserta.dashboard');
});

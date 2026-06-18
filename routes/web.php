<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\EventController;

Route::get('/', [EventController::class, 'index'])->name('landing');

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

    // Admin Events
    Route::get('/admin/events', [EventController::class, 'adminIndex'])->name('admin.events.index');
    Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');

    Route::get('/peserta/dashboard', function () {
        return view('dashboard_peserta.index');
    })->name('peserta.dashboard');

    // Event Registration
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
});

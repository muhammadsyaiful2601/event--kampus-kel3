<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\LocaleController;

Route::get('lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

Route::get('/', [EventController::class, 'index'])->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register/otp', [AuthController::class, 'showOtpVerification'])->name('register.otp');
Route::post('/register/otp', [AuthController::class, 'verifyOtp'])->name('register.otp.verify');
Route::post('/register/otp/resend', [AuthController::class, 'resendOtp'])->name('register.otp.resend');

Route::get('/lupa_password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/lupa_password', [AuthController::class, 'sendResetOtp'])->name('password.reset.send');
Route::get('/lupa_password/otp', [AuthController::class, 'showResetOtpForm'])->name('password.reset.otp');
Route::post('/lupa_password/otp', [AuthController::class, 'verifyResetOtp'])->name('password.reset.verify');
Route::post('/lupa_password/otp/resend', [AuthController::class, 'resendResetOtp'])->name('password.reset.otp.resend');
Route::get('/lupa_password/reset', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/lupa_password/reset', [AuthController::class, 'resetPassword'])->name('password.reset.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboard_admin.index');
    })->name('admin.dashboard');

    // Admin Events
    Route::get('/admin/events', [EventController::class, 'adminIndex'])->name('admin.events.index');
    Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');

    // Admin Registrations
    Route::get('/admin/registrations', [EventController::class, 'adminRegistrations'])->name('admin.registrations.index');
    Route::post('/admin/registrations/{registration}/verify', [EventController::class, 'verifyRegistration'])->name('admin.registrations.verify');
    Route::put('/admin/events/{event}', [EventController::class, 'update'])->name('admin.events.update');
    Route::delete('/admin/events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');

    // Manajemen Admin
    Route::get('/admin/admins', [AdminManagementController::class, 'index'])->name('admin.admins.index');
    Route::post('/admin/admins', [AdminManagementController::class, 'store'])->name('admin.admins.store');
    Route::delete('/admin/admins/{admin}', [AdminManagementController::class, 'destroy'])->name('admin.admins.destroy');

    Route::get('/peserta/dashboard', [EventController::class, 'pesertaIndex'])->name('peserta.dashboard');

    // Event Registration
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
});

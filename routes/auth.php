<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\SiPintuAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Minimal)
|--------------------------------------------------------------------------
| Hanya menyisakan Login, Logout, dan Profile.
| Register, Reset Password, dan Verifikasi Email dinonaktifkan.
*/

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// OAuth 2.0 / OpenID Connect SSO SiPintu (Manual Redirect endpoint)
Route::get('/oauth/sipintu', [SiPintuAuthController::class, 'redirect'])->name('oauth.sipintu.redirect');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Profile (hanya untuk user yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// (Opsional) Konfirmasi password sebelum aksi sensitif
Route::middleware('auth')->group(function () {
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);
});

// (Opsional) Update password dari profile
Route::middleware('auth')->group(function () {
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;

// Route Tamu (Guest) - Cuma bisa diakses kalau belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
});

// Route Login (Auth) - Cuma bisa diakses kalau SUDAH login
Route::middleware('auth')->group(function () {
    // Dashboard Utama
    Route::get('/', function () {
        return view('layouts.admin');
    })->name('dashboard');

    // Route Master Barang
    Route::resource('barang', BarangController::class);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
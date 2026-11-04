<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LaporanController;
use App\Http\Middleware\HanyaAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute TemuBarang
|--------------------------------------------------------------------------
| 1. Tamu (belum login)  : hanya halaman login & registrasi.
| 2. Pengguna login      : beranda, laporan, pencarian, laporan saya.
| 3. Admin               : dashboard admin (dijaga middleware HanyaAdmin).
| Tamu yang membuka halaman lain otomatis diarahkan ke halaman login.
*/

// 1. Tamu
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'formLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.proses');
    Route::get('/daftar', [AuthController::class, 'formRegistrasi'])->name('register');
    Route::post('/daftar', [AuthController::class, 'registrasi'])->name('register.proses');
});

// 2. Pengguna login
Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [BerandaController::class, 'index'])->name('home');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/buat', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan-saya', [LaporanController::class, 'saya'])->name('laporan.saya');

    Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{laporan}/ubah', [LaporanController::class, 'edit'])->name('laporan.edit');
    Route::put('/laporan/{laporan}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::patch('/laporan/{laporan}/status', [LaporanController::class, 'ubahStatus'])->name('laporan.status');
    Route::delete('/laporan/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');

    // 3. Admin
    Route::middleware(HanyaAdmin::class)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        });
});

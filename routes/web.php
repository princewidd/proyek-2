<?php

use App\Http\Controllers\Admin\AkunGameController;
use App\Http\Controllers\BeliController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// BERANDA
Route::get('/', [GameController::class, 'index'])
    ->name('beranda');

// DETAIL GAME
Route::get('/game/{slug}', [GameController::class, 'show'])
    ->name('game.show');

// DETAIL AKUN
Route::get('/game/{slug}/account/{akun}', [GameController::class, 'showAkun'])
    ->name('game.akun.show');

// DETAIL JOKI
Route::get('/game/{slug}/joki/{kategori?}', [GameController::class, 'showJoki'])
    ->name('game.joki.show');

// TRANSAKSI PELANGGAN
Route::middleware('auth')->group(function () {
    Route::post('/beli/{akunGame}', [BeliController::class, 'store'])
        ->name('beli.akun.store');

    Route::post('/joki-beli', [BeliController::class, 'storeJoki'])
        ->name('beli.joki.store');

    Route::get('/pembayaran/{pembayaran}', [BeliController::class, 'show'])
        ->name('pembayaran.show');
});

// DASHBOARD ADMIN
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// CRUD AKUN GAME ADMIN
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/akun/create', [AkunGameController::class, 'create'])
        ->name('admin.akun.create');

    Route::post('/admin/akun', [AkunGameController::class, 'store'])
        ->name('admin.akun.store');

    Route::get('/admin/akun/{akunGame}/edit', [AkunGameController::class, 'edit'])
        ->name('admin.akun.edit');

    Route::put('/admin/akun/{akunGame}', [AkunGameController::class, 'update'])
        ->name('admin.akun.update');

    Route::delete('/admin/akun/{akunGame}', [AkunGameController::class, 'destroy'])
        ->name('admin.akun.destroy');
});

// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterUserController;

use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('beranda');

Route::get('/game/{slug}', [GameController::class, 'show'])->name('game.show');

Route::get('/game/{slug}/account/{akun}', [GameController::class, 'showAkun'])->name('game.akun.show');

Route::get('/game/{slug}/joki/{kategori?}', [GameController::class, 'showJoki'])->name('game.joki.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

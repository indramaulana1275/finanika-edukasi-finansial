<?php

use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// =============================
// Dasbor Edukasi Finansial
// =============================
Route::get('/dashboard', [EdukasiController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Kuis Risiko
Route::get('/kuis-risiko', [EdukasiController::class, 'kuisRisiko'])
    ->middleware('auth')
    ->name('edukasi.kuis');

Route::post('/kuis-risiko', [EdukasiController::class, 'simpanKuis'])
    ->middleware('auth')
    ->name('edukasi.kuis.simpan');

// Kalkulator DCA
Route::get('/kalkulator-dca', [EdukasiController::class, 'kalkulatorDCA'])
    ->middleware('auth')
    ->name('edukasi.kalkulator');

Route::post('/kalkulator-dca', [EdukasiController::class, 'hitungDCA'])
    ->middleware('auth')
    ->name('edukasi.kalkulator.hitung');

// =============================
// Market Data
// =============================
Route::get('/market', [EdukasiController::class, 'marketData'])
    ->middleware('auth')
    ->name('edukasi.market');

// API Endpoint (untuk AJAX/Fetch)
Route::get('/api/market/current', [EdukasiController::class, 'apiCurrentPrices'])
    ->middleware('auth')
    ->name('api.market.current');

Route::get('/api/market/history', [EdukasiController::class, 'apiHistoricalPrices'])
    ->middleware('auth')
    ->name('api.market.history');

// =============================
// Narasi Edukasi AI (NLG - Gemini)
// =============================
Route::get('/narasi-edukasi', [EdukasiController::class, 'narasiEdukasi'])
    ->middleware('auth')
    ->name('edukasi.narasi');

// =============================
// Route Profil Bawaan Breeze
// =============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
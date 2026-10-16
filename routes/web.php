<?php

use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\EducationController;
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

// Market Detail: Crypto
Route::get('/market/crypto', [EdukasiController::class, 'cryptoDetail'])->middleware('auth')->name('edukasi.crypto');

// Market Detail: Forex
Route::get('/market/forex', [EdukasiController::class, 'forexDetail'])->middleware('auth')->name('edukasi.forex');

// Market Detail: Saham US
Route::get('/market/saham-us', [EdukasiController::class, 'sahamUsDetail'])
    ->middleware('auth')
    ->name('edukasi.saham-us');

// Market Detail: Komoditas
Route::get('/market/commodity', [EdukasiController::class, 'commodityDetail'])
    ->middleware('auth')
    ->name('edukasi.commodity');

// Asset Detail: Halaman detail per aset (crypto, stock, forex, commodity, index)
Route::get('/market/asset/{type}/{symbol}', [EdukasiController::class, 'assetDetail'])
    ->middleware('auth')
    ->name('edukasi.asset.detail');

// Portfolio
Route::get('/portfolio', [EdukasiController::class, 'portfolio'])->middleware('auth')->name('portfolio');
Route::post('/portfolio/add', [EdukasiController::class, 'storePortfolio'])->middleware('auth')->name('portfolio.store');
Route::delete('/portfolio/{holding}', [EdukasiController::class, 'destroyPortfolio'])->middleware('auth')->name('portfolio.destroy');

// Chat FinGo AI
Route::middleware('auth')->group(function () {
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'send'])
        ->middleware('throttle:15,1')
        ->name('chat.send');
    Route::delete('/chat/clear', [\App\Http\Controllers\ChatController::class, 'clear'])->name('chat.clear');
});

// =============================
// Modul Edukasi Interaktif (FASE I)
// =============================
Route::middleware('auth')->group(function () {
    Route::get('/education', [EducationController::class, 'index'])->name('education.index');
    Route::get('/education/{slug}', [EducationController::class, 'show'])->name('education.show');
    Route::post('/education/{slug}/submit', [EducationController::class, 'submitQuiz'])->name('education.submit');
    Route::get('/education/certificate', [EducationController::class, 'certificate'])->name('education.certificate');
});

require __DIR__.'/auth.php';

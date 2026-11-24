<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EtlController;
use App\Http\Controllers\ExecutiveDashboardController;
use App\Http\Controllers\ExecutiveSummaryController;
use App\Http\Controllers\WhatIfController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman untuk tamu
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.attempt');
});

/*
|--------------------------------------------------------------------------
| Halaman yang wajib login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Beranda
    Route::view('/', 'home')->name('home');

    // Executive Information System
    Route::get('/executive-summary', [ExecutiveSummaryController::class, 'index'])
        ->name('executive-summary');

    Route::get('/dashboard', [ExecutiveDashboardController::class, 'index'])
        ->name('dashboard');

    // What-If: Discount Policy Simulation
    Route::get('/what-if/discount', [WhatIfController::class, 'discount'])
        ->name('what-if.discount');

    // What-If: Regional Growth Allocation
    Route::get('/what-if/growth', [WhatIfController::class, 'growth'])
        ->name('what-if.growth');

    /*
    |--------------------------------------------------------------------------
    | ETL Data Management (Semi-Otomatis 5 Tahap + Staging + History)
    |--------------------------------------------------------------------------
    */

    // Halaman utama ETL Data Management (Stepper + Import History)
    Route::get('/etl', [EtlController::class, 'index'])
        ->name('etl');

    // Tahap 1 -> 2 -> 3 -> 4: Upload, Extract Otomatis, Transform & Validate Otomatis
    Route::post('/etl/process', [EtlController::class, 'process'])
        ->name('etl.process');

    // Tahap 4 -> 5: Konfirmasi Load ke Database
    Route::post('/etl/load', [EtlController::class, 'load'])
        ->name('etl.load');

    // Reset / Bersihkan Staging Session
    Route::post('/etl/reset', [EtlController::class, 'reset'])
        ->name('etl.reset');

    // Halaman Import CSV (Legacy/Direct route tetap dipertahankan)
    Route::get('/etl/import', [EtlController::class, 'importPage'])
        ->name('etl.import.page');

    // Halaman Input Manual
    Route::get('/etl/manual', [EtlController::class, 'manualPage'])
        ->name('etl.manual.page');

    // Halaman Kondisi Data
    Route::get('/etl/condition', [EtlController::class, 'condition'])
        ->name('etl.condition');

    // Proses Import Replace (Legacy)
    Route::post('/etl/replace', [EtlController::class, 'replace'])
        ->name('etl.replace');

    // Proses Import Append (Legacy)
    Route::post('/etl/append', [EtlController::class, 'append'])
        ->name('etl.append');

    // Proses Input Manual
    Route::post('/etl/manual', [EtlController::class, 'manual'])
        ->name('etl.manual');

    // Export CSV
    Route::get('/etl/export', [EtlController::class, 'export'])
        ->name('etl.export');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

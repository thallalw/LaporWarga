<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ProfileController; 

// Halaman Utama (Bisa diakses tanpa login untuk melihat laporan)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Autentikasi
Route::get('/login', fn () => redirect()->route('dashboard'));
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'registerWarga'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang Membutuhkan Login
Route::middleware('auth')->group(function () {
    
    // Fitur Profil (Warga & Admin)
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Fitur Laporan (Warga)
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::post('/report/{report}/update', [ReportController::class, 'update'])->name('report.update');
    Route::post('/report/{report}/delete', [ReportController::class, 'destroy'])->name('report.destroy');
    
    // Interaksi Laporan (Bisa Warga & Admin)
    Route::post('/report/{report}/like', [ReportController::class, 'like'])->name('report.like');
    Route::post('/report/{report}/archive', [ReportController::class, 'toggleArchive'])->name('report.archive');

    // Fitur Khusus Admin (RT/RW)
    Route::middleware('role:admin')->group(function () {
        Route::post('/report/{report}/status', [ReportController::class, 'updateStatus'])->name('report.status');
        Route::post('/announcement', [AnnouncementController::class, 'store'])->name('announcement.store');
        Route::post('/announcement/{announcement}/update', [AnnouncementController::class, 'update'])->name('announcement.update'); // BARU
        Route::post('/announcement/{announcement}/delete', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');
    });
});
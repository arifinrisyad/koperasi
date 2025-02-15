<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\BarangDijualController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DetailedFinanceController;
use App\Http\Controllers\PetugasController;

// Rute otentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard routes based on role
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware(['auth', 'role:petugas'])->group(function () {
        Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
    });

    // User management routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
        Route::get('/users/online-status', [UserController::class, 'getOnlineStatus']);
        Route::get('/check-status', [UserController::class, 'checkStatus']);
    });

    // CRUD Resources
    Route::resources([
        'barang-dijual' => BarangDijualController::class,
        'pembelian' => PembelianController::class,
        'penjualan' => PenjualanController::class,
        'laporan-pembelian' => LaporanPembelianController::class,
        'laporan-penjualan' => LaporanPenjualanController::class,
    ]);

    // Route Keuangan
    Route::prefix('keuangan')->group(function () {
        // Report routes first
        Route::get('/laporan', [KeuanganController::class, 'laporan'])->name('keuangan.laporan');
        Route::get('/export-laporan', [KeuanganController::class, 'exportLaporan'])->name('keuangan.export-laporan');
        Route::get('/export/data', [KeuanganController::class, 'export'])->name('keuangan.export');
        
        // Then CRUD routes
        Route::get('/', [KeuanganController::class, 'index'])->name('keuangan.index');
        Route::get('/create', [KeuanganController::class, 'create'])->name('keuangan.create');
        Route::post('/', [KeuanganController::class, 'store'])->name('keuangan.store');
        Route::get('/{keuangan}/edit', [KeuanganController::class, 'edit'])->name('keuangan.edit');
        Route::put('/{keuangan}', [KeuanganController::class, 'update'])->name('keuangan.update');
        Route::delete('/{keuangan}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
        Route::get('/{keuangan}', [KeuanganController::class, 'show'])->name('keuangan.show');
    });

    // Laporan Penjualan Routes
    Route::get('laporan-penjualan/fetch-data', [LaporanPenjualanController::class, 'fetchData'])
        ->name('laporan-penjualan.fetch-data');
    Route::get('laporan-penjualan/{id}/print', [LaporanPenjualanController::class, 'print'])
        ->name('laporan-penjualan.print');
    Route::get('laporan-penjualan/{id}/export-pdf', [LaporanPenjualanController::class, 'exportPdf'])
        ->name('laporan-penjualan.export-pdf');

    // Laporan Pembelian Routes
    Route::prefix('laporan-pembelian')->group(function () {
        Route::get('/fetch-data', [LaporanPembelianController::class, 'fetchData'])->name('laporan-pembelian.fetch-data');
        Route::get('/{id}/print', [LaporanPembelianController::class, 'print'])->name('laporan-pembelian.print');
        Route::get('/{id}/export-pdf', [LaporanPembelianController::class, 'exportPdf'])->name('laporan-pembelian.export-pdf');
    });

    // Profile Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    });

    Route::get('laporan/index', function () {
        return view('laporan.index');
    })->name('laporan.index');

    // Pembelian Routes
    Route::delete('/pembelian-hapus-semua', [PembelianController::class, 'destroyAll'])->name('pembelian.destroy-all');
    Route::post('/pembelian/store', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');

    // Laporan Routes
    Route::get('/laporan-penjualan/pdf', [LaporanPenjualanController::class, 'exportToPDF'])->name('laporan-penjualan.pdf');
    Route::get('/laporan-penjualan/excel', [LaporanPenjualanController::class, 'exportToExcel'])->name('laporan-penjualan.excel');
    Route::delete('laporan-penjualan/{id}', [LaporanPenjualanController::class, 'destroy'])->name('laporan-penjualan.destroy');
    Route::post('laporan-penjualan/fetch-data', [LaporanPenjualanController::class, 'fetchData'])->name('laporan-penjualan.fetch');

    // Activity Logs Routes
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('activity-logs/{activityLog}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::get('activity-logs-clear', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');
    Route::get('activity-logs-export', [ActivityLogController::class, 'export'])->name('activity-logs.export');

    Route::get('/test-log', function () {
        $log = App\Models\ActivityLog::first();
        dd($log);
    });

    Route::get('/test-logs', function () {
        $logs = App\Models\ActivityLog::all();
        dd($logs->toArray());
    });

    Route::get('/report/generate', [ReportController::class, 'generateReport'])->name('report.generate');

    // Keuangan Routes
    Route::get('/keuangan/laporan', [KeuanganController::class, 'laporan'])->name('keuangan.laporan');
    Route::get('/keuangan/export-laporan', [KeuanganController::class, 'exportLaporan'])->name('keuangan.export-laporan');
});
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPesertaController;
use App\Http\Controllers\Admin\AdminKelasController;
use App\Http\Controllers\Admin\AdminRiwayatController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Kasir\KasirPesertaController;
use App\Http\Controllers\Kasir\KasirTransaksiController;
use App\Http\Controllers\Kasir\KasirRiwayatController; 
use App\Http\Controllers\Kasir\KasirLogController;      

// Auth
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ================================
// KASIR ROUTES
// ================================
Route::prefix('kasir')->middleware(['auth', RoleMiddleware::class . ':kasir'])->group(function () {

    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('kasir.dashboard');

    // PESERTA
    Route::prefix('peserta')->group(function () {
        Route::get('/',          [KasirPesertaController::class, 'index'])->name('kasir.peserta.index');
        Route::get('/add',       [KasirPesertaController::class, 'add'])->name('kasir.peserta.add');
        Route::post('/',         [KasirPesertaController::class, 'store'])->name('kasir.peserta.store');
        Route::get('/{id}/edit', [KasirPesertaController::class, 'edit'])->name('kasir.peserta.edit');
        Route::put('/{id}',      [KasirPesertaController::class, 'update'])->name('kasir.peserta.update');
    });

    // TRANSAKSI
    Route::prefix('transaksi')->group(function () {
        Route::get('/',            [KasirTransaksiController::class, 'index'])->name('kasir.transaksi.index');
        Route::get('/{id}/bayar',  [KasirTransaksiController::class, 'bayar'])->name('kasir.transaksi.bayar');
        Route::post('/{id}/proses',[KasirTransaksiController::class, 'proses'])->name('kasir.transaksi.proses');
        Route::delete('/{id}',     [KasirTransaksiController::class, 'destroy'])->name('kasir.transaksi.destroy');
    });

    // RIWAYAT
    Route::prefix('riwayat')->group(function () {
        Route::get('/',          [KasirRiwayatController::class, 'index'])->name('kasir.riwayat.index');
    });

    // LOG
    Route::prefix('log')->group(function () {
        Route::get('/', [KasirLogController::class, 'index'])->name('kasir.log.index');
    });

}); // ← tutup prefix('kasir')


// ================================
// ADMIN ROUTES
// ================================
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // USER MANAGEMENT
    Route::prefix('user')->group(function () {
        Route::get('/',          [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/add',       [AdminUserController::class, 'add'])->name('admin.users.add');
        Route::post('/',         [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/{id}',      [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::get('/{id}/toggle',[AdminUserController::class, 'toggle'])->name('admin.users.toggle');
    });

    // PESERTA
    Route::prefix('peserta')->group(function () {
        Route::get('/',     [AdminPesertaController::class, 'index'])->name('admin.peserta.index');
        Route::delete('/{id}', [AdminPesertaController::class, 'destroy'])->name('admin.peserta.delete');
    });

    // KELAS
    Route::prefix('kelas')->group(function () {
        Route::get('/',          [AdminKelasController::class, 'index'])->name('admin.kelas.index');
        Route::get('/add',       [AdminKelasController::class, 'add'])->name('admin.kelas.add');
        Route::post('/',         [AdminKelasController::class, 'store'])->name('admin.kelas.store');
        Route::get('/{id}/edit', [AdminKelasController::class, 'edit'])->name('admin.kelas.edit');
        Route::put('/{id}',      [AdminKelasController::class, 'update'])->name('admin.kelas.update');
        Route::delete('/{id}',   [AdminKelasController::class, 'destroy'])->name('admin.kelas.delete');
    });

    // LOG
    Route::prefix('log')->group(function () {
        Route::get('/', [AdminLogController::class, 'index'])->name('admin.log.index');
    });

    // RIWAYAT TRANSAKSI
    Route::get('riwayat', [AdminRiwayatController::class, 'index'])->name('admin.riwayat');
    Route::get('riwayat/{id}/struk', function ($id) {
        $riwayat = \App\Models\Transaksi::with(['tagihan.peserta.kelas', 'user'])->findOrFail($id);
        return view('Admin.Riwayat.struk', compact('riwayat'));
    })->name('admin.riwayat.struk');

}); // ← tutup prefix('admin')


// ================================
// OWNER ROUTES
// ================================
Route::prefix('owner')->middleware(['auth', RoleMiddleware::class . ':owner'])->group(function () {

    Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('users',     [OwnerUserController::class, 'index'])->name('owner.users');
    Route::get('kelas',     [OwnerKelasController::class, 'index'])->name('owner.kelas');
    Route::get('log',       [OwnerLogController::class, 'index'])->name('owner.log');
    Route::get('laporan',   [OwnerLaporanController::class, 'index'])->name('owner.laporan');

});
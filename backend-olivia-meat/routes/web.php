<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\PengeluaranController;

// Halaman Utama / Dashboard (Ringkasan Keuangan & Laba Rugi)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Rute Transaksi Pesanan (Create & Read)
Route::get('/transaksi', [PesananController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi/store', [PesananController::class, 'store'])->name('transaksi.store');

// Rute Piutang & Update Pembayaran Cicilan
Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');
Route::post('/piutang/bayar/{id}', [PiutangController::class, 'updatePembayaran'])->name('piutang.bayar');

// Rute Pengeluaran Harian
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::post('/pengeluaran/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
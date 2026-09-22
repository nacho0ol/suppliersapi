<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PengeluaranHarian;
use App\Models\Piutang;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Pemasukan dari Pesanan yang berstatus Lunas / sudah dibayar
        $totalPemasukan = Pesanan::where('is_dibatalkan', 0)
            ->where('status_bayar', 'Lunas')
            ->join('pesanan_detail', 'pesanan.id_pesanan', '=', 'pesanan_detail.id_pesanan')
            ->sum(\DB::raw('pesanan_detail.qty * pesanan_detail.harga_jual_saat_ini'));

        // 2. Total Pengeluaran Harian
        $totalPengeluaran = PengeluaranHarian::where('is_deleted', 0)->sum('nominal');

        // 3. Hitung Laba Bersih Sederhana (Pemasukan - Pengeluaran)
        $labaRugi = $totalPemasukan - $totalPengeluaran;

        // 4. Hitung Piutang Belum Lunas
        $totalPiutang = Piutang::where('is_deleted', 0)->where('status_piutang', '!=', 'Lunas')->sum(\DB::raw('total_tagihan - jumlah_terbayar'));

        return view('dashboard', compact('totalPemasukan', 'totalPengeluaran', 'labaRugi', 'totalPiutang'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Piutang;
use App\Models\PengeluaranHarian;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'labarugi');

        // Data Laba Rugi
        $totalPemasukan = Pesanan::where('is_dibatalkan', 0)
            ->where('status_bayar', 'Lunas')
            ->join('pesanan_detail', 'pesanan.id_pesanan', '=', 'pesanan_detail.id_pesanan')
            ->sum(\DB::raw('pesanan_detail.qty * pesanan_detail.harga_jual_saat_ini'));

        $totalPengeluaran = PengeluaranHarian::where('is_deleted', 0)->sum('nominal');
        $labaRugi = $totalPemasukan - $totalPengeluaran;

        // Data Riwayat Transaksi & Piutang & Pengeluaran
        $pesanan = Pesanan::where('is_dibatalkan', 0)->orderBy('tgl_order', 'desc')->get();
        $piutang = Piutang::where('is_deleted', 0)->get();
        $pengeluaran = PengeluaranHarian::where('is_deleted', 0)->get();

        return view('keuangan.index', compact('tab', 'totalPemasukan', 'totalPengeluaran', 'labaRugi', 'pesanan', 'piutang', 'pengeluaran'));
    }
}
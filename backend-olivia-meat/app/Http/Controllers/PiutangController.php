<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Piutang;

class PiutangController extends Controller
{
    // Menampilkan daftar piutang (Belum Lunas / Sebagian)
    public function index()
    {
        $piutang = Piutang::where('is_deleted', 0)->orderBy('tgl_jatuh_tempo', 'asc')->get();
        return view('piutang.index', compact('piutang'));
    }

    // Memproses pembayaran piutang (Cicil atau Lunas)
    public function updatePembayaran(Request $request, $id_pesanan)
    {
        $request->validate([
            'jumlah_bayar' => 'required|integer|min:1'
        ]);

        $piutang = Piutang::where('id_pesanan', $id_pesanan)->firstOrFail();

        // Hitung total terbayar yang baru
        $terbayarBaru = $piutang->jumlah_terbayar + $request->jumlah_bayar;

        // Validasi agar tidak kelebihan bayar dari total tagihan
        if ($terbayarBaru > $piutang->total_tagihan) {
            return redirect()->back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan!');
        }

        // Tentukan status piutang otomatis
        if ($terbayarBaru == $piutang->total_tagihan) {
            $status = 'Lunas';
        } elseif ($terbayarBaru > 0) {
            $status = 'Sebagian';
        } else {
            $status = 'Belum Lunas';
        }

        // Update data piutang
        $piutang->update([
            'jumlah_terbayar' => $terbayarBaru,
            'status_piutang' => $status
        ]);

        return redirect()->back()->with('success', 'Pembayaran piutang berhasil diperbarui!');
    }
}
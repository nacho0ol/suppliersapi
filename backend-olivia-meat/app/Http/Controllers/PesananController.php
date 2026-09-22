<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Piutang;
use App\Models\Produk;

class PesananController extends Controller
{
    // Menampilkan halaman daftar pesanan
    public function index()
    {
        // Mengambil data pesanan yang belum dihapus/dibatalkan
        $pesanan = Pesanan::where('is_dibatalkan', 0)->orderBy('tgl_order', 'desc')->get();
        return view('transaksi.index', compact('pesanan'));
    }

    // Memproses form transaksi baru (Header + Detail + Piutang)
    public function store(Request $request)
    {
        // 1. Validasi input dari frontend
        $request->validate([
            'id_pelanggan' => 'required|integer',
            'metode_bayar' => 'required|in:Tunai,Transfer,Tempo',
            'tgl_antar' => 'required|date',
            'produk_id' => 'required|array', // Array produk yang dibeli
            'qty' => 'required|array',       // Array jumlah qty per produk
        ]);

        // Mulai Database Transaction (Biar aman kalau ada error di tengah jalan)
        DB::beginTransaction();

        try {
            // 2. Generate ID Pesanan Otomatis (Contoh: ORD-20260922-01)
            $tanggalSekarang = date('Ymd');
            $nomorUrut = Pesanan::whereDate('tgl_order', date('Y-m-d'))->count() + 1;
            $id_pesanan = 'ORD-' . $tanggalSekarang . '-' . str_pad($nomorUrut, 2, '0', STR_PAD_LEFT);

            // 3. Insert ke Tabel Pesanan (Header)
            // status_bayar otomatis disesuaikan dengan metode
            $status_bayar = ($request->metode_bayar == 'Tempo') ? 'Unpaid' : 'Lunas';
            
            Pesanan::create([
                'id_pesanan' => $id_pesanan,
                'id_pelanggan' => $request->id_pelanggan,
                'id_user' => 1, // Sementara hardcode ID Admin 1, nanti diganti auth()->user()->id_user
                'tgl_order' => date('Y-m-d'),
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => $status_bayar,
                'tgl_antar' => $request->tgl_antar,
                'status_pemesanan' => 'Diterima',
                'is_dibatalkan' => 0
            ]);

            $total_tagihan = 0;

            // 4. Insert ke Tabel Pesanan Detail (Isi Keranjang) looping dari array
            foreach ($request->produk_id as $index => $id_produk) {
                // Ambil harga terbaru dari database master produk
                $produk = Produk::findOrFail($id_produk);
                $qty = $request->qty[$index];
                
                PesananDetail::create([
                    'id_pesanan' => $id_pesanan,
                    'id_produk' => $id_produk,
                    'qty' => $qty,
                    'harga_jual_saat_ini' => $produk->harga_jual,
                    'is_deleted' => 0
                ]);

                // Kalkulasi manual untuk total tagihan (qty * harga)
                $total_tagihan += ($qty * $produk->harga_jual);
            }

            // 5. Otomatis Insert ke Tabel Piutang JIKA metode bayarnya Tempo
            if ($request->metode_bayar == 'Tempo') {
                Piutang::create([
                    'id_pesanan' => $id_pesanan,
                    // Asumsi jatuh tempo default H+7 dari tanggal order
                    'tgl_jatuh_tempo' => date('Y-m-d', strtotime('+7 days')),
                    'total_tagihan' => $total_tagihan,
                    'jumlah_terbayar' => 0,
                    'status_piutang' => 'Belum Lunas',
                    'is_deleted' => 0
                ]);
            }

            // Jika semua sukses, simpan permanen ke database
            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            // Jika ada 1 saja proses yang gagal, batalkan semuanya
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
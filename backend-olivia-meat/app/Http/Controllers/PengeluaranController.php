<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranHarian;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluaran = PengeluaranHarian::where('is_deleted', 0)->orderBy('tgl_pengeluaran', 'desc')->get();
        return view('pengeluaran.index', compact('pengeluaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_pengeluaran' => 'required|date',
            'kategori_pengeluaran' => 'required|in:Makan,Pembelian Daging,Operasional,Transportasi,Lain-lain',
            'nominal' => 'required|integer|min:1000',
            'keterangan' => 'required|string|min:5'
        ]);

        PengeluaranHarian::create([
            'tgl_pengeluaran' => $request->tgl_pengeluaran,
            'kategori_pengeluaran' => $request->kategori_pengeluaran,
            'id_user' => 1, // Hardcode ID Admin sementara
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'is_deleted' => 0
        ]);

        return redirect()->back()->with('success', 'Catatan pengeluaran harian berhasil ditambahkan!');
    }
}
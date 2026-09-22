<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pelanggan;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        // Default tab produk, bisa switch ke pelanggan
        $tab = $request->get('tab', 'produk');

        $produk = Produk::where('is_deleted', 0)->get();
        $pelanggan = Pelanggan::where('is_deleted', 0)->get();

        return view('master.index', compact('produk', 'pelanggan', 'tab'));
    }

    // Simpan Produk Baru
    public function storeProduk(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|min:3',
            'kategori' => 'required|in:Daging,Tulang,Buntut,Jeroan,Kaki',
            'harga_awal' => 'required|integer|min:1000',
            'harga_jual' => 'required|integer|gte:harga_awal',
            'nama_jagal' => 'required|string|min:3'
        ]);

        Produk::create(array_merge($request->all(), ['is_deleted' => 0]));
        return redirect()->route('master.index', ['tab' => 'produk'])->with('success', 'Produk baru berhasil disimpan!');
    }

    // Hapus (Soft Delete) Produk
    public function deleteProduk($id)
    {
        Produk::where('id_produk', $id)->update(['is_deleted' => 1]);
        return redirect()->route('master.index', ['tab' => 'produk'])->with('success', 'Produk berhasil dihapus.');
    }

    // Simpan Pelanggan Baru
    public function storePelanggan(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|min:3',
            'no_telp' => 'required|regex:/^08[0-9]{8,13}$/',
            'alamat' => 'required|string|min:5'
        ]);

        Pelanggan::create(array_merge($request->all(), ['is_deleted' => 0]));
        return redirect()->route('master.index', ['tab' => 'pelanggan'])->with('success', 'Pelanggan baru berhasil disimpan!');
    }

    // Hapus (Soft Delete) Pelanggan
    public function deletePelanggan($id)
    {
        Pelanggan::where('id_pelanggan', $id)->update(['is_deleted' => 1]);
        return redirect()->route('master.index', ['tab' => 'pelanggan'])->with('success', 'Pelanggan berhasil dihapus.');
    }
}
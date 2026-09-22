@extends('layouts.app')

@section('content')
<div>
    <h2 class="font-bold text-gray-700 text-base mb-3">Kelola Data Master</h2>

    <!-- Switch Tab Button -->
    <div class="flex border-b border-gray-200 mb-4">
        <a href="{{ route('master.index', ['tab' => 'produk']) }}" class="flex-1 text-center py-2 text-xs font-semibold {{ $tab == 'produk' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500' }}">
            Tab Produk
        </a>
        <a href="{{ route('master.index', ['tab' => 'pelanggan']) }}" class="flex-1 text-center py-2 text-xs font-semibold {{ $tab == 'pelanggan' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500' }}">
            Tab Pelanggan
        </a>
    </div>

    @if($tab == 'produk')
        <!-- KONTEN TAB PRODUK -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-gray-600">Tambah Produk Baru</h3>
            <form action="{{ route('master.produk.store') }}" method="POST" class="bg-gray-50 p-3 rounded border space-y-2">
                @csrf
                <input type="text" name="nama_produk" placeholder="Nama Produk (Cth: Daging Sirloin)" required class="w-full text-xs border p-2 rounded">
                <select name="kategori" class="w-full text-xs border p-2 rounded">
                    <option value="Daging">Daging</option>
                    <option value="Tulang">Tulang</option>
                    <option value="Buntut">Buntut</option>
                    <option value="Jeroan">Jeroan</option>
                    <option value="Kaki">Kaki</option>
                </select>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" name="harga_awal" placeholder="Harga Awal" required class="text-xs border p-2 rounded">
                    <input type="number" name="harga_jual" placeholder="Harga Jual" required class="text-xs border p-2 rounded">
                </div>
                <input type="text" name="nama_jagal" placeholder="Nama Jagal / RPH" required class="w-full text-xs border p-2 rounded">
                <button type="submit" class="w-full bg-red-600 text-white text-xs py-2 rounded font-semibold">Simpan Produk</button>
            </form>

            <h3 class="text-xs font-bold text-gray-600 mt-4">Distribusi List Produk</h3>
            @foreach($produk as $p)
                <div class="bg-white border rounded p-2 text-xs flex justify-between items-center shadow-sm">
                    <div>
                        <span class="font-bold text-red-600">{{ $p->nama_produk }}</span> ({{ $p->kategori }})
                        <p class="text-gray-500">Jual: Rp {{ number_format($p->harga_jual, 0, ',', '.') }} | Jagal: {{ $p->nama_jagal }}</p>
                    </div>
                    <form action="{{ route('master.produk.delete', $p->id_produk) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 font-bold px-2 py-1 bg-red-50 rounded">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <!-- KONTEN TAB PELANGGAN -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-gray-600">Tambah Pelanggan Baru</h3>
            <form action="{{ route('master.pelanggan.store') }}" method="POST" class="bg-gray-50 p-3 rounded border space-y-2">
                @csrf
                <input type="text" name="nama_pelanggan" placeholder="Nama Pelanggan" required class="w-full text-xs border p-2 rounded">
                <input type="text" name="no_telp" placeholder="No Telp (Cth: 08123456789)" required class="w-full text-xs border p-2 rounded">
                <textarea name="alamat" placeholder="Alamat Lengkap" required class="w-full text-xs border p-2 rounded"></textarea>
                <button type="submit" class="w-full bg-red-600 text-white text-xs py-2 rounded font-semibold">Simpan Pelanggan</button>
            </form>

            <h3 class="text-xs font-bold text-gray-600 mt-4">Daftar Pelanggan</h3>
            @foreach($pelanggan as $pl)
                <div class="bg-white border rounded p-2 text-xs flex justify-between items-center shadow-sm">
                    <div>
                        <span class="font-bold text-blue-600">{{ $pl->nama_pelanggan }}</span>
                        <p class="text-gray-500">{{ $pl->no_telp }} - {{ $pl->alamat }}</p>
                    </div>
                    <form action="{{ route('master.pelanggan.delete', $pl->id_pelanggan) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 font-bold px-2 py-1 bg-red-50 rounded">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
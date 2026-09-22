@extends('layouts.app')

@section('content')
<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-gray-700 text-lg">Daftar Pesanan</h2>
        <!-- Tombol Trigger Form Tambah (Bisa pakai modal / redirect) -->
    </div>

    <div class="space-y-3">
        @forelse($pesanan as $p)
            <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-xs font-bold text-red-600">{{ $p->id_pesanan }}</span>
                    <p class="text-sm font-semibold text-gray-800">Pelanggan ID: {{ $p->id_pelanggan }}</p>
                    <p class="text-xs text-gray-500">Antar: {{ $p->tgl_antar }} | Metode: {{ $p->metode_bayar }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-1 rounded {{ $p->status_bayar == 'Lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $p->status_bayar }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 text-sm py-8">Belum ada data transaksi.</p>
        @endforelse
    </div>
</div>
@endsection
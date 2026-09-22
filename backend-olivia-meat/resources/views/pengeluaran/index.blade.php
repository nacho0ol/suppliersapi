@extends('layouts.app')

@section('content')
<div>
    <h2 class="font-bold text-gray-700 text-lg mb-4">Catatan Pengeluaran Harian</h2>

    <div class="space-y-3">
        @forelse($pengeluaran as $pen)
            <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-xs font-bold text-orange-600">{{ $pen->kategori_pengeluaran }}</span>
                    <p class="text-sm font-semibold text-gray-800">Rp {{ number_format($pen->nominal, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">{{ $pen->keterangan }}</p>
                </div>
                <div class="text-right text-xs text-gray-400">
                    {{ $pen->tgl_pengeluaran }}
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 text-sm py-8">Belum ada catatan pengeluaran.</p>
        @endforelse
    </div>
</div>
@endsection
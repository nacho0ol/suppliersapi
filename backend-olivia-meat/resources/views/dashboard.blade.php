@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded shadow-sm">
        <h2 class="text-sm text-gray-500 font-semibold">ESTIMASI LABA BERSIH</h2>
        <p class="text-2xl font-bold text-red-700">Rp {{ number_format($labaRugi, 0, ',', '.') }}</p>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="bg-green-50 p-3 rounded shadow-sm border border-green-100">
            <p class="text-xs text-gray-500">Total Pemasukan</p>
            <p class="text-base font-bold text-green-700">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-orange-50 p-3 rounded shadow-sm border border-orange-100">
            <p class="text-xs text-gray-500">Total Pengeluaran</p>
            <p class="text-base font-bold text-orange-700">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-blue-50 p-4 rounded shadow-sm border border-blue-100">
        <p class="text-xs text-gray-500">Total Piutang Tempo Belum Lunas</p>
        <p class="text-xl font-bold text-blue-700">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</p>
    </div>

    <div class="pt-2">
        <a href="{{ route('transaksi.index') }}" class="block text-center bg-red-600 text-white py-3 rounded-lg font-semibold shadow hover:bg-red-700">
            + Catat Pesanan Baru
        </a>
    </div>
</div>
@endsection
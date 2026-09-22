@extends('layouts.app')

@section('content')
<div>
    <h2 class="font-bold text-gray-700 text-lg mb-4">Monitoring Piutang Pelanggan</h2>

    <div class="space-y-3">
        @forelse($piutang as $p)
            <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-xs font-bold text-blue-600">{{ $p->id_pesanan }}</span>
                    <p class="text-sm font-semibold text-gray-800">Tagihan: Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Jatuh Tempo: {{ $p->tgl_jatuh_tempo }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-1 rounded {{ $p->status_piutang == 'Lunas' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $p->status_piutang }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 text-sm py-8">Belum ada data piutang tempo.</p>
        @endforelse
    </div>
</div>
@endsection
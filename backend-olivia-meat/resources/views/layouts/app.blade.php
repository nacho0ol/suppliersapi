<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olivia Meat N Fresh</title>
    <!-- Tailwind CSS CDN buat styling cepat & responsif -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center">

    <!-- Container utama dibatasi mirip layar HP (Max width mobile view) -->
    <div class="w-full max-w-md bg-white min-h-screen shadow-lg flex flex-col justify-between relative pb-16">
        
        <!-- Header Atas -->
        <header class="bg-red-700 text-white p-4 sticky top-0 z-50 flex justify-between items-center shadow">
            <h1 class="font-bold text-lg">🥩 Olivia Meat N Fresh</h1>
            <span class="text-xs bg-red-800 px-2 py-1 rounded">Admin</span>
        </header>

        <!-- Konten Utama (Berubah-ubah sesuai halaman) -->
        <main class="p-4 flex-1">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Navigation Bar Bawah (Mobile View Style) -->
        <nav class="w-full max-w-md bg-white border-t border-gray-200 fixed bottom-0 flex justify-around py-3 shadow-inner z-50">
            <a href="{{ route('dashboard') }}" class="text-center text-xs text-gray-600 hover:text-red-700">
                <span class="block text-lg">🏠</span> Dashboard
            </a>
            <a href="{{ route('transaksi.index') }}" class="text-center text-xs text-gray-600 hover:text-red-700">
                <span class="block text-lg">📦</span> Transaksi
            </a>
            <a href="{{ route('piutang.index') }}" class="text-center text-xs text-gray-600 hover:text-red-700">
                <span class="block text-lg">💰</span> Piutang
            </a>
            <a href="{{ route('pengeluaran.index') }}" class="text-center text-xs text-gray-600 hover:text-red-700">
                <span class="block text-lg">📉</span> Pengeluaran
            </a>
        </nav>

    </div>

</body>
</html>
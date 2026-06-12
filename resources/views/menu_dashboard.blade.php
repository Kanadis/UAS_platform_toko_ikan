<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Toko Ikan - Beranda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3">
                <!-- Menu Kiri -->
                <div class="flex space-x-6">
                    <a href="{{ route('beranda') }}" class="hover:text-blue-200 transition duration-300">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                    <a href="#" class="hover:text-blue-200 transition duration-300">
                        <i class="fas fa-history mr-1"></i> Riwayat
                    </a>
                    <a href="#" class="hover:text-blue-200 transition duration-300">
                        <i class="fas fa-shopping-cart mr-1"></i> Keranjang
                    </a>
                    <a href="#" class="hover:text-blue-200 transition duration-300">
                        <i class="fas fa-user mr-1"></i> Akun
                    </a>
                </div>

                <!-- Nama Toko di Kanan -->
                <div class="text-xl font-bold">
                    <i class="fas fa-fish mr-2"></i>Toko Ikan
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('beranda') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="search" class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-search mr-1"></i> Cari Ikan
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama ikan..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="w-full md:w-48">
                    <label for="jenis" class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-filter mr-1"></i> Semua Jenis
                    </label>
                    <select name="jenis" id="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Jenis</option>
                        <option value="konsumsi" {{ request('jenis') == 'konsumsi' ? 'selected' : '' }}>Ikan Konsumsi</option>
                        <option value="bibit" {{ request('jenis') == 'bibit' ? 'selected' : '' }}>Bibit Ikan</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center gap-2">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Ikan Konsumsi Section -->
        @if(!$jenis || $jenis == 'konsumsi')
            @if($produkKonsumsi->count() > 0)
            <div class="mb-12">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 h-8 w-2 rounded-full mr-3"></div>
                    <h2 class="text-2xl font-bold text-gray-800">Ikan Konsumsi</h2>
                    <span class="ml-3 bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs">
                        {{ $produkKonsumsi->count() }} produk
                    </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($produkKonsumsi as $item)
                    <div class="product-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-800">{{ $item->nama_ikan }}</h3>
                                <i class="fas fa-fish text-blue-500 text-2xl"></i>
                            </div>
                            <div class="mt-3 space-y-2">
                                <p class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-weight-hanging text-gray-400 w-5"></i> 
                                    <span>{{ $item->keterangan }}</span>
                                </p>
                                <p class="text-2xl font-bold text-blue-600">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    <span class="text-sm font-normal text-gray-500">/{{ $item->satuan }}</span>
                                </p>
                                @if($item->stok_berat > 0)
                                    <p class="text-green-600 text-xs">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </p>
                                @else
                                    <p class="text-red-600 text-xs">
                                        <i class="fas fa-times-circle"></i> Habis
                                    </p>
                                @endif
                            </div>
                            <button class="mt-4 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center gap-2" 
                                    onclick="pesanProduk('{{ $item->nama_ikan }}', {{ $item->harga }})">
                                <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif

        <!-- Bibit Ikan Section -->
        @if(!$jenis || $jenis == 'bibit')
            @if($produkBibit->count() > 0)
            <div>
                <div class="flex items-center mb-6">
                    <div class="bg-green-600 h-8 w-2 rounded-full mr-3"></div>
                    <h2 class="text-2xl font-bold text-gray-800">Bibit Ikan</h2>
                    <span class="ml-3 bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">
                        {{ $produkBibit->count() }} produk
                    </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($produkBibit as $item)
                    <div class="product-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-800">{{ $item->nama_ikan }}</h3>
                                <i class="fas fa-seedling text-green-500 text-2xl"></i>
                            </div>
                            <div class="mt-3 space-y-2">
                                <p class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-ruler-combined text-gray-400 w-5"></i> 
                                    <span>{{ $item->keterangan }}</span>
                                </p>
                                <p class="text-2xl font-bold text-green-600">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    <span class="text-sm font-normal text-gray-500">/{{ $item->satuan }}</span>
                                </p>
                                @if($item->stok_berat > 0)
                                    <p class="text-green-600 text-xs">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </p>
                                @else
                                    <p class="text-red-600 text-xs">
                                        <i class="fas fa-times-circle"></i> Habis
                                    </p>
                                @endif
                            </div>
                            <button class="mt-4 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center gap-2"
                                    onclick="pesanProduk('{{ $item->nama_ikan }}', {{ $item->harga }})">
                                <i class="fas fa-shopping-cart"></i> Pesan Bibit
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif

        <!-- Jika tidak ada hasil -->
        @if(($produkKonsumsi->count() == 0 && (!$jenis || $jenis == 'konsumsi')) || 
            ($produkBibit->count() == 0 && (!$jenis || $jenis == 'bibit')))
            <div class="text-center py-12 bg-white rounded-lg shadow-md">
                <i class="fas fa-fish fa-5x text-gray-300 mb-4"></i>
                @if($search)
                    <p class="text-gray-500 text-xl">Ikan "{{ $search }}" tidak ditemukan</p>
                    <p class="text-gray-400 text-sm mt-2">Coba kata kunci lain atau lihat semua produk</p>
                @else
                    <p class="text-gray-500 text-xl">Belum ada produk tersedia</p>
                    <p class="text-gray-400 text-sm mt-2">Silakan tambahkan produk melalui database</p>
                @endif
            </div>
        @endif
    </div>

    <!-- Bottom Navigation (Mobile Style) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 md:hidden shadow-lg">
        <div class="flex justify-around py-3">
            <a href="{{ route('beranda') }}" class="flex flex-col items-center text-blue-600">
                <i class="fas fa-home text-xl"></i>
                <span class="text-xs mt-1">Beranda</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-600">
                <i class="fas fa-history text-xl"></i>
                <span class="text-xs mt-1">Riwayat</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-600">
                <i class="fas fa-shopping-cart text-xl"></i>
                <span class="text-xs mt-1">Keranjang</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-600">
                <i class="fas fa-user text-xl"></i>
                <span class="text-xs mt-1">Akun</span>
            </a>
        </div>
    </div>

    <!-- Padding bottom untuk mobile -->
    <div class="pb-20 md:pb-0"></div>

    <script>
        function pesanProduk(nama, harga) {
            alert('🛒 ' + nama + '\nHarga: Rp ' + new Intl.NumberFormat('id-ID').format(harga) + '\n\nFitur pesanan akan segera tersedia!');
        }
    </script>

</body>
</html>
{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Toko Ikan')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CSS Custom --}}
    <style>
        .auth-card {
            max-width: 400px;
            margin: 50px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .auth-brand {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            color: #0d6efd;
        }
        
        .auth-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.2);
        }
        
        .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #0d6efd !important;
        }
        
        .btn-outline-primary:hover {
            color: white !important;
        }
    </style>

    @yield('styles')
</head>
<body class="bg-light">

{{-- ==================== NAVBAR ==================== --}}
{{-- Sembunyikan navbar di halaman login dan register --}}
@if(!request()->routeIs('login') && !request()->routeIs('register'))
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top shadow-sm">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand fw-bold text-primary" href="{{ route('beranda') }}">
            <i class="bi bi-fish"></i> Toko Ikan
        </a>

        {{-- Tombol hamburger (mobile) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            {{-- Menu kiri --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}" 
                       href="{{ route('beranda') }}">
                        <i class="bi bi-house"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('riwayat') ? 'active' : '' }}" 
                       href="{{ route('riwayat') }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('keranjang') ? 'active' : '' }}" 
                       href="{{ route('keranjang') }}">
                        <i class="bi bi-cart"></i> Keranjang
                    </a>
                </li>
            </ul>

            {{-- Menu kanan --}}
            <div class="d-flex align-items-center gap-2">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->nama }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i> Profil
                                </a>
                            </li>
                            @if(Auth::user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary" href="#">
                                        <i class="bi bi-speedometer2 me-2"></i> Dashboard Admin
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ==================== FLASH MESSAGE ==================== --}}
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif
@endif

{{-- ==================== KONTEN HALAMAN ==================== --}}
<main class="container py-4">
    @yield('content')
</main>

{{-- ==================== FOOTER ==================== --}}
@if(!request()->routeIs('login') && !request()->routeIs('register'))
<footer class="border-top mt-5 py-4">
    <div class="container text-center text-secondary" style="font-size: 13px;">
        <p class="mb-0">&copy; {{ date('Y') }} Toko Ikan. Semua hak dilindungi.</p>
    </div>
</footer>
@endif

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- JS tambahan --}}
@yield('scripts')

<script>
    // Fungsi global untuk pesan produk
    function pesanProduk(nama, harga) {
        alert('🛒 ' + nama + '\nHarga: Rp ' + new Intl.NumberFormat('id-ID').format(harga) + '\n\nFitur pesanan akan segera tersedia!');
    }
</script>

</body>
</html>
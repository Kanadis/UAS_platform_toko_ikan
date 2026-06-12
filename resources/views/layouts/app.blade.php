{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Judul halaman bisa diubah tiap halaman --}}
    <title>@yield('title', 'Toko Ikan')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CSS Custom --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- CSS tambahan khusus halaman tertentu --}}
    @yield('styles')
</head>
<body>

{{-- ==================== NAVBAR ==================== --}}
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand" href="{{ route('produk.index') }}">
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
                    <a class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}"
                       href="{{ route('produk.index') }}">
                        <i class="bi bi-grid"></i> Produk
                    </a>
                </li>
            </ul>

            {{-- Menu kanan --}}
            <div class="d-flex align-items-center gap-2">

                @auth
                    {{-- Keranjang --}}
                    <a href="{{ route('keranjang.index') }}"
                       class="btn btn-outline-secondary btn-sm position-relative">
                        <i class="bi bi-cart3"></i> Keranjang
                        {{-- Badge jumlah item di keranjang --}}
                        {{-- Nanti diisi: --}}
                        {{-- @if ($jumlahKeranjang > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $jumlahKeranjang }}
                            </span>
                        @endif --}}
                    </a>

                    {{-- Dropdown user --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->nama }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('transaksi.riwayat') }}">
                                    <i class="bi bi-clock-history me-2"></i> Riwayat pesanan
                                </a>
                            </li>

                            @if (Auth::user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Dashboard admin
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
                    {{-- Belum login --}}
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth

            </div>
        </div>
    </div>
</nav>

{{-- ==================== FLASH MESSAGE ==================== --}}
@if (session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 13px; border-radius: 8px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 13px; border-radius: 8px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

{{-- ==================== KONTEN HALAMAN ==================== --}}
<main class="container py-4">
    @yield('content')
</main>

{{-- ==================== FOOTER ==================== --}}
<footer class="border-top mt-5 py-4">
    <div class="container text-center text-secondary" style="font-size: 13px;">
        <p class="mb-0">&copy; {{ date('Y') }} Toko Ikan. Semua hak dilindungi.</p>
    </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- JS tambahan khusus halaman tertentu --}}
@yield('scripts')

</body>
</html>
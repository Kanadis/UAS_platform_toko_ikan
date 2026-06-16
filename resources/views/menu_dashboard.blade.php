{{-- resources/views/menu_dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Beranda - Toko Ikan')

@section('content')
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('beranda') }}" class="row g-2 align-items-center">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama ikan..." class="form-control border-start-0 ps-0">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>
        </form>
    </div>
</div>

@if(isset($produk) && $produk->count() > 0)
<div class="mb-5">
    <div class="d-flex align-items-center mb-3 border-bottom pb-2">
        <div class="bg-primary h-100 me-2" style="width: 4px; border-radius: 2px;"></div>
        <h4 class="mb-0 fw-bold">Daftar Ikan</h4>
        <span class="badge bg-primary ms-2">{{ $produk->count() }} produk</span>
    </div>
    
    <div class="row g-4">
        @foreach($produk as $item)
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 product-card shadow-sm border-0">
                <div class="text-center bg-light" style="height: 180px; overflow: hidden; border-radius: var(--bs-border-radius) var(--bs-border-radius) 0 0;">
                    @if(isset($item->foto) && $item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_ikan }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="bi bi-image text-muted opacity-50" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                </div>

                <div class="card-body d-flex flex-column p-3">
                    <h5 class="card-title text-primary fw-bold mb-2">{{ $item->nama_ikan }}</h5>
                    
                    <p class="card-text text-muted small mb-2">
                        <i class="bi bi-box-seam"></i> Stok: <span class="fw-bold">{{ number_format($item->stok_berat, 1) }} kg</span>
                    </p>
                    <h4 class="text-danger fw-bold mb-3">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                        <small class="text-muted fs-6 fw-normal">/kg</small>
                    </h4>
                    
                    @if(($item->stok_berat ?? 0) > 0)
                        <span class="badge bg-success mb-3" style="width: fit-content;">Tersedia</span>
                        
                        {{-- 🛡️ FILTER ROLE ADMIN 🛡️ --}}
                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <button class="btn btn-outline-secondary w-100 mt-auto fw-bold" disabled>
                                <i class="bi bi-shield-lock me-1"></i> Mode Admin
                            </button>
                        @else
                            <form action="{{ route('keranjang.tambah', $item->id) }}" method="POST" class="mt-auto">
                                @csrf
                                <div class="input-group mb-3 shadow-sm rounded">
                                    <button class="btn btn-outline-secondary fw-bold" type="button" onclick="kurangi(this)">-</button>
                                    <input type="number" 
                                           class="form-control text-center fw-bold" 
                                           name="jumlah" 
                                           value="1" 
                                           min="0.1" 
                                           step="0.1" 
                                           max="{{ $item->stok_berat }}"
                                           oninput="validasiInput(this)">
                                    <span class="input-group-text bg-light text-muted">kg</span>
                                    <button class="btn btn-outline-secondary fw-bold" type="button" onclick="tambah(this)">+</button>
                                </div>

                                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                                    <i class="bi bi-cart-plus me-1"></i> Pesan Sekarang
                                </button>
                            </form>
                        @endif
                        {{-- ======================== --}}

                    @else
                        <span class="badge bg-danger mb-3" style="width: fit-content;">Habis</span>
                        <button class="btn btn-secondary w-100 mt-auto" disabled>Stok Kosong</button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
    <div class="text-center py-5">
        <i class="bi bi-fish fs-1 text-muted opacity-50 mb-3 d-block"></i>
        @if(request('search'))
            <p class="text-muted">Ikan "{{ request('search') }}" tidak ditemukan.</p>
            <a href="{{ route('beranda') }}" class="btn btn-outline-primary btn-sm mt-2">Tampilkan Semua</a>
        @else
            <p class="text-muted">Belum ada produk yang dijual saat ini.</p>
        @endif
    </div>
@endif
@endsection

@section('scripts')
<script>
    function tambah(btn) {
        let input = btn.parentElement.querySelector('input[name="jumlah"]');
        let max = parseFloat(input.max);
        let val = parseFloat(input.value) || 0;
        if (val < max) {
            input.value = (val + 0.1).toFixed(1); 
        } else {
            alert('Maksimal stok yang tersedia adalah ' + max + ' kg.');
        }
    }

    function kurangi(btn) {
        let input = btn.parentElement.querySelector('input[name="jumlah"]');
        let val = parseFloat(input.value) || 0;
        if (val > 0.1) {
            input.value = (val - 0.1).toFixed(1);
        }
    }

    function validasiInput(input) {
        let max = parseFloat(input.max);
        let val = parseFloat(input.value);
        if (val > max) {
            input.value = max;
            alert('Wah, melebihi stok! Maksimal pembelian hanya ' + max + ' kg ya.');
        }
    }
</script>
@endsection
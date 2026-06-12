{{-- resources/views/menu_dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Beranda - Toko Ikan')

@section('content')
<!-- Search Section -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('beranda') }}" class="row g-3">
            <div class="col-md-10">
                <label for="search" class="form-label">
                    <i class="bi bi-search"></i> Cari Ikan
                </label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nama ikan..." 
                       class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ikan Konsumsi Section -->
@if(isset($produkKonsumsi) && $produkKonsumsi->count() > 0)
<div class="mb-5">
    <div class="d-flex align-items-center mb-3">
        <div class="bg-primary h-100" style="width: 4px; height: 30px; border-radius: 2px;"></div>
        <h3 class="ms-2 mb-0">Ikan Konsumsi</h3>
        <span class="badge bg-primary ms-2">{{ $produkKonsumsi->count() }} produk</span>
    </div>
    <div class="row g-4">
        @foreach($produkKonsumsi as $item)
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 product-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title text-primary mb-0">{{ $item->nama_ikan }}</h5>
                        <i class="bi bi-fish text-primary fs-3"></i>
                    </div>
                    <p class="card-text text-muted small">
                        <i class="bi bi-info-circle"></i> {{ $item->keterangan ?? $item->stok_berat . ' kg tersedia' }}
                    </p>
                    <h4 class="text-primary mb-3">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                        <small class="text-muted fs-6">/{{ $item->satuan ?? 'kg' }}</small>
                    </h4>
                    @if(($item->stok_berat ?? 0) > 0)
                        <span class="badge bg-success mb-2">Tersedia</span>
                    @else
                        <span class="badge bg-danger mb-2">Habis</span>
                    @endif
                    <button class="btn btn-success w-100" 
                            onclick="pesanProduk('{{ $item->nama_ikan }}', {{ $item->harga }})">
                        <i class="bi bi-cart-plus"></i> Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Bibit Ikan Section -->
@if(isset($produkBibit) && $produkBibit->count() > 0)
<div class="mb-5">
    <div class="d-flex align-items-center mb-3">
        <div class="bg-success h-100" style="width: 4px; height: 30px; border-radius: 2px;"></div>
        <h3 class="ms-2 mb-0">Bibit Ikan</h3>
        <span class="badge bg-success ms-2">{{ $produkBibit->count() }} produk</span>
    </div>
    <div class="row g-4">
        @foreach($produkBibit as $item)
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 product-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title text-success mb-0">{{ $item->nama_ikan }}</h5>
                        <i class="bi bi-flower1 text-success fs-3"></i>
                    </div>
                    <p class="card-text text-muted small">
                        <i class="bi bi-info-circle"></i> {{ $item->keterangan ?? $item->stok_berat . ' ekor tersedia' }}
                    </p>
                    <h4 class="text-success mb-3">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                        <small class="text-muted fs-6">/{{ $item->satuan ?? 'ekor' }}</small>
                    </h4>
                    @if(($item->stok_berat ?? 0) > 0)
                        <span class="badge bg-success mb-2">Tersedia</span>
                    @else
                        <span class="badge bg-danger mb-2">Habis</span>
                    @endif
                    <button class="btn btn-success w-100" 
                            onclick="pesanProduk('{{ $item->nama_ikan }}', {{ $item->harga }})">
                        <i class="bi bi-cart-plus"></i> Pesan Bibit
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Jika tidak ada hasil -->
@if((!isset($produkKonsumsi) || $produkKonsumsi->count() == 0) && 
    (!isset($produkBibit) || $produkBibit->count() == 0))
    <div class="text-center py-5">
        <i class="bi bi-fish fs-1 text-muted"></i>
        @if(request('search'))
            <p class="text-muted mt-3">Ikan "{{ request('search') }}" tidak ditemukan</p>
            <a href="{{ route('beranda') }}" class="btn btn-outline-primary btn-sm">Lihat semua produk</a>
        @else
            <p class="text-muted mt-3">Belum ada produk tersedia</p>
        @endif
    </div>
@endif
@endsection 
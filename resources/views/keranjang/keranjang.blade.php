{{-- resources/views/keranjang/keranjang.blade.php --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja - Toko Ikan')

@section('content')
<div class="container mb-5">
    <h2 class="mb-4 text-primary"><i class="bi bi-cart3"></i> Keranjang Belanja</h2>


    @if($keranjang && $keranjang->DetailKeranjang->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4 py-3">Produk</th>
                                <th scope="col" class="py-3">Harga Satuan</th>
                                <th scope="col" class="text-center py-3">Jumlah</th>
                                <th scope="col" class="text-end pe-4 py-3">Subtotal</th>
                                <th scope="col" class="text-center pe-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalHarga = 0; @endphp
                            
                            @foreach($keranjang->DetailKeranjang as $detail)
                                @php 
                                    // Hitung subtotal (harga ikan x jumlah beli)
                                    $subtotal = $detail->Produk->harga * $detail->jumlah;
                                    $totalHarga += $subtotal; // Tambahkan ke total keseluruhan
                                @endphp
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3">
                                                @if(strpos(strtolower($detail->Produk->jenis_ikan), 'bibit') !== false)
                                                    <i class="bi bi-flower1 fs-4 text-success"></i>
                                                @else
                                                    <i class="bi bi-fish fs-4 text-primary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $detail->Produk->nama_ikan }}</h6>
                                                <small class="text-muted">{{ $detail->Produk->jenis_ikan }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">Rp {{ number_format($detail->Produk->harga, 0, ',', '.') }}</td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-secondary px-3 py-2 fs-6">
                                            {{ $detail->jumlah }} {{ $detail->Produk->satuan }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 py-3 fw-bold text-primary">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4 py-3">
                                        <form action="{{ route('keranjang.hapus', $detail->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuang ikan ini dari keranjang?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Produk">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white p-4 border-top-0">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <a href="{{ route('beranda') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Lanjut Belanja
                        </a>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="mb-3">
                            <span class="text-muted me-2">Total Pembayaran:</span>
                            <span class="fs-3 fw-bold text-success">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('checkout.halaman') }}" class="btn btn-success btn-lg px-5 shadow-sm">
                            Checkout Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white rounded shadow-sm border-0">
            <i class="bi bi-cart-x fs-1 text-muted mb-3 d-block"></i>
            <h4 class="text-muted">Keranjang belanja Anda masih kosong</h4>
            <p class="text-muted mb-4">Yuk, cari ikan segar pilihanmu sekarang!</p>
            <a href="{{ route('beranda') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-search"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
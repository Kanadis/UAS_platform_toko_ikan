{{-- resources/views/transaksi/checkout.blade.php --}}

@extends('layouts.app')

@section('title', 'Checkout - Toko Ikan')

@section('content')
<div class="container mb-5">
    <h3 class="mb-4 text-dark"><i class="bi bi-bag-check"></i> Checkout Pesanan</h3>

    <form action="{{ route('checkout.proses') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h6 class="text-danger fw-bold mb-3"><i class="bi bi-geo-alt-fill"></i> Alamat Pengiriman</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 fw-bold">{{ Auth::user()->name }} <span class="text-muted fw-normal">({{ $alamat->nomor_telp_alamat ?? '-' }})</span></p>
                                <p class="mb-0 text-muted small">{{ $alamat->alamat_lengkap ?? 'Alamat belum diatur' }}</p>
                            </div>
                            {{-- PERUBAHAN DI SINI: Tautan Ubah kini mengarah ke form alamat dan mengirim status source=checkout --}}
                            <a href="{{ route('profil.alamat', ['source' => 'checkout']) }}" class="text-decoration-none small text-primary">Ubah ></a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom bg-light">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-shop"></i> Toko Ikan</h6>
                        </div>
                        @php $totalHarga = 0; @endphp
                        @foreach($keranjang->DetailKeranjang as $detail)
                            @php 
                                $subtotal = $detail->Produk->harga * $detail->jumlah;
                                $totalHarga += $subtotal;
                            @endphp
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-fish fs-3 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $detail->Produk->nama_ikan }}</h6>
                                    <small class="text-muted d-block mb-2">Jenis: {{ $detail->Produk->jenis_ikan }}</small>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-danger fw-bold">Rp {{ number_format($detail->Produk->harga, 0, ',', '.') }}</span>
                                        <span class="text-muted small">x{{ $detail->jumlah }} {{ $detail->Produk->satuan }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Metode Pembayaran</h6>
                        
                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input ms-1 mt-2" type="radio" name="metode_pembayaran" id="metode_cod" value="COD" checked>
                            <label class="form-check-label ms-3 w-100 cursor-pointer" for="metode_cod">
                                <i class="bi bi-cash-coin text-success me-2"></i> Cash on Delivery (COD)
                            </label>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input ms-1 mt-2" type="radio" name="metode_pembayaran" id="metode_transfer" value="Transfer Bank">
                            <label class="form-check-label ms-3 w-100 cursor-pointer" for="metode_transfer">
                                <i class="bi bi-bank text-primary me-2"></i> Transfer Bank
                            </label>
                        </div>

                        <div class="form-check p-3 border rounded">
                            <input class="form-check-input ms-1 mt-2" type="radio" name="metode_pembayaran" id="metode_ewallet" value="E-Wallet">
                            <label class="form-check-label ms-3 w-100 cursor-pointer" for="metode_ewallet">
                                <i class="bi bi-wallet2 text-info me-2"></i> E-Wallet (OVO, Dana, ShopeePay)
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Rincian Pembayaran</h6>
                        
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Subtotal Pesanan</span>
                            <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Biaya Pengiriman</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small text-muted border-bottom pb-3">
                            <span>Biaya Layanan</span>
                            <span>Rp 0</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold">Total Pembayaran</span>
                            <span class="fs-4 fw-bold text-danger">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm">
                            Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
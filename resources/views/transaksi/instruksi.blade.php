{{-- resources/views/transaksi/instruksi.blade.php --}}

@extends('layouts.app')

@section('title', 'Instruksi Pembayaran - Toko Ikan')

@section('content')
<div class="container mb-5 d-flex justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 mt-4 text-center">
            <div class="card-body p-5">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h3 class="mt-3 fw-bold">Pesanan Berhasil Dibuat!</h3>
                <p class="text-muted">Nomor Pesanan: <strong>{{ session('order_id') }}</strong></p>

                <hr class="my-4">

                <h5 class="mb-3">Total yang harus dibayar:</h5>
                <h2 class="text-danger fw-bold mb-4">Rp {{ number_format(session('total_harga'), 0, ',', '.') }}</h2>

                <div class="bg-light p-4 rounded text-start">
                    @if(session('metode') == 'COD')
                        <h6 class="fw-bold"><i class="bi bi-cash-coin text-success me-2"></i> Cash on Delivery (COD)</h6>
                        <p class="mb-0 text-muted small">Silakan siapkan uang tunai pas sejumlah total tagihan di atas. Pembayaran dilakukan langsung kepada kurir saat ikan segar Anda tiba di lokasi.</p>
                    
                    @elseif(session('metode') == 'Transfer Bank')
                        <h6 class="fw-bold"><i class="bi bi-bank text-primary me-2"></i> Transfer Bank (Virtual Account)</h6>
                        <p class="text-muted small mb-2">Silakan transfer ke nomor Virtual Account (BRIVA/BCA) berikut:</p>
                        <div class="d-flex justify-content-between align-items-center bg-white border p-2 rounded">
                            <span class="fs-5 fw-bold text-dark tracking-wide">8801 0812 3456 7890</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="alert('Nomor disalin!')"><i class="bi bi-clipboard"></i> Salin</button>
                        </div>
                        <p class="text-muted small mt-2 mb-0">Pesanan akan otomatis diproses setelah pembayaran terverifikasi.</p>

                    @elseif(session('metode') == 'E-Wallet')
                        <h6 class="fw-bold"><i class="bi bi-wallet2 text-info me-2"></i> Pembayaran E-Wallet (DANA/OVO/ShopeePay)</h6>
                        <p class="text-muted small mb-2">Silakan transfer saldo ke nomor telepon Toko Ikan berikut:</p>
                        <div class="d-flex justify-content-between align-items-center bg-white border p-2 rounded">
                            <span class="fs-5 fw-bold text-dark tracking-wide">0899 8877 6655</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="alert('Nomor disalin!')"><i class="bi bi-clipboard"></i> Salin</button>
                        </div>
                        <p class="text-muted small mt-2 mb-0">Atas Nama: <strong>Toko Ikan Official</strong></p>
                    @endif
                </div>

                <div class="mt-5">
                    <a href="{{ route('status.pesanan') }}" class="btn btn-outline-primary px-4 me-2">Cek Status Pesanan</a>
                    <a href="{{ route('beranda') }}" class="btn btn-primary px-4">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
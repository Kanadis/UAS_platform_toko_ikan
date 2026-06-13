{{-- resources/views/transaksi/status.blade.php --}}

@extends('layouts.app')

@section('title', 'Status Pesanan - Toko Ikan')

@section('content')
<div class="container mb-5">
    <h3 class="mb-4 text-dark"><i class="bi bi-box-seam"></i> Status Pesanan Aktif</h3>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($transaksi->count() > 0)
        @foreach($transaksi as $trx)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small me-3"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y, H:i') }}</span>
                        <span class="fw-bold small text-secondary">INV-{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('Ymd') }}-{{ $trx->id }}</span>
                    </div>
                    
                    @if($trx->status == 'menunggu_pembayaran')
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split"></i> Menunggu Pembayaran</span>
                    @elseif($trx->status == 'diproses')
                        <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="bi bi-gear-fill"></i> Sedang Diproses</span>
                    @elseif($trx->status == 'dikirim')
                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><i class="bi bi-truck"></i> Sedang Dikirim</span>
                    @endif
                </div>

                <div class="card-body">
                    @foreach($trx->detailTransaksi as $detail)
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded p-3 me-3">
                                <i class="bi bi-fish fs-4 text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $detail->produk->nama_ikan }}</h6>
                                <small class="text-muted">{{ $detail->jumlah }} {{ $detail->produk->satuan }} x Rp {{ number_format($detail->harga_saat_beli, 0, ',', '.') }}</small>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fw-bold text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="card-footer bg-light border-top-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Belanja</p>
                        <h5 class="mb-0 fw-bold text-danger">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</h5>
                    </div>
                    <div>
                        @if($trx->status == 'menunggu_pembayaran')
                            <form action="{{ route('transaksi.batal', $trx->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok ikan akan dikembalikan ke toko.');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm px-4">Batal</button>
                            </form>
                            
                            <button type="button" class="btn btn-primary btn-sm px-4 ms-2" data-bs-toggle="modal" data-bs-target="#modalBayar{{ $trx->id }}">
                                Bayar Sekarang
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            @if($trx->status == 'menunggu_pembayaran')
            <div class="modal fade text-start" id="modalBayar{{ $trx->id }}" tabindex="-1" aria-labelledby="modalBayarLabel{{ $trx->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        
                        <form action="{{ route('transaksi.bayar', $trx->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fw-bold" id="modalBayarLabel{{ $trx->id }}">Upload Bukti Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-4">
                                <div class="alert alert-info small border-0 bg-light text-dark shadow-sm">
                                    <i class="bi bi-info-circle-fill text-primary me-2"></i> 
                                    Silakan pilih metode yang digunakan dan unggah foto bukti transfer Anda.
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" class="form-select" required>
                                        <option value="" disabled selected>-- Pilih Metode --</option>
                                        <option value="transfer_bank">Transfer Bank (Virtual Account)</option>
                                        <option value="ewallet">E-Wallet (DANA/OVO/ShopeePay)</option>
                                        <option value="cod">Cash on Delivery (COD)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label fw-bold small text-muted">Foto Bukti Pembayaran</label>
                                    <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/png, image/jpeg, image/jpg" required>
                                    <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">*Format JPG/PNG, Maksimal 2MB. Untuk metode COD, Anda bisa mengunggah foto bebas (struk kosong) sebagai formalitas sistem.</small>
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 pt-0">
                                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">Kirim Bukti</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            
        @endforeach
    @else
        <div class="text-center py-5 bg-white rounded shadow-sm border-0">
            <i class="bi bi-box2 fs-1 text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Tidak ada pesanan aktif saat ini.</h5>
            <a href="{{ route('beranda') }}" class="btn btn-primary mt-3 px-4 shadow-sm">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection
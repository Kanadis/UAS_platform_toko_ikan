@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail Pesanan #{{ $transaction->id }}</div>
                <div class="card-body">
                    <h5>Pelanggan: {{ $transaction->user->nama ?? '-' }}</h5>
                    <p>Email: {{ $transaction->user->email ?? '-' }}</p>

                    <h5>Produk yang Dipesan</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Saat Beli</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->detailTransaksi as $detail)
                            <tr>
                                <td>{{ $detail->produk->nama_ikan ?? '-' }}</td>
                                <td>{{ $detail->jumlah }} kg</td>
                                <td>Rp {{ number_format($detail->harga_saat_beli, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <h5 class="text-end">Total: Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Update Status</div>
                <div class="card-body">
                    <form action="{{ route('admin.order.update-status', $transaction->id) }}" method="POST">
                        @csrf @method('PUT')
                        <select name="status" class="form-control mb-2">
                            <option value="menunggu_pembayaran" {{ $transaction->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="diproses" {{ $transaction->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikirim" {{ $transaction->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ $transaction->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $transaction->status == 'dibatalkan' ? 'selected' : '' }}>Batalkan</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">Bukti Pembayaran</div>
                <div class="card-body">
                    @if($transaction->pembayaran && $transaction->pembayaran->bukti_pembayaran)
                        <a href="{{ asset('storage/' . $transaction->pembayaran->bukti_pembayaran) }}" target="_blank">Lihat Bukti</a>
                    @else
                        <p class="text-muted">Belum ada bukti diunggah.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
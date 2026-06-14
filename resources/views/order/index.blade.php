@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Pesanan</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->user->nama ?? '-' }}</td>
                <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                <td>
                    @if($transaction->status == 'menunggu_pembayaran')
                        <span class="badge bg-warning">Menunggu Pembayaran</span>
                    @elseif($transaction->status == 'diproses')
                        <span class="badge bg-info">Diproses</span>
                    @elseif($transaction->status == 'dikirim')
                        <span class="badge bg-primary">Dikirim</span>
                    @elseif($transaction->status == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @elseif($transaction->status == 'dibatalkan')
                        <span class="badge bg-danger">Dibatalkan</span>
                    @else
                        <span class="badge bg-secondary">{{ $transaction->status }}</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.order.show', $transaction->id) }}" class="btn btn-sm btn-primary">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5">Belum ada pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $transactions->links() }}
</div>
@endsection
@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container">
    <h1 class="mb-4">Riwayat Pesanan</h1>

    @if($riwayat->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $trans)
                    <tr>
                        <td>{{ $trans->id }}</td>
                        <td>{{ $trans->created_at->format('d/m/Y H:i') }}</td>
                        <td>Rp {{ number_format($trans->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if($trans->status == 'diproses')
                                <span class="badge bg-info">Diproses</span>
                            @elseif($trans->status == 'dikirim')
                                <span class="badge bg-primary">Dikirim</span>
                            @elseif($trans->status == 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($trans->status == 'dibatalkan')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @else
                                <span class="badge bg-secondary">{{ $trans->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('produk.show', $trans->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $riwayat->links() }}
    @else
        <div class="alert alert-info">Belum ada riwayat pesanan.</div>
    @endif
</div>
@endsection
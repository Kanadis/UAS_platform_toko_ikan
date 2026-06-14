{{-- resources/views/admin/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard Admin - Toko Ikan')

@section('styles')
<style>
    .stat-card {
        border-radius: 12px;
        border: none;
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .table th { font-size: 13px; color: #6c757d; font-weight: 600; }
    .foto-thumb {
        width: 50px; height: 50px;
        object-fit: cover; border-radius: 8px;
    }
    .foto-placeholder {
        width: 50px; height: 50px; border-radius: 8px;
        background: #e9ecef;
        display: flex; align-items: center; justify-content: center;
        color: #adb5bd; font-size: 20px;
    }
</style>
@endsection

@section('content')

{{-- ==================== HEADER ==================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Admin</h4>
        <small class="text-muted">Kelola produk toko ikan</small>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </button>
</div>

{{-- ==================== STAT CARD ==================== --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-fish fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Produk</div>
                    <div class="fw-bold fs-4">{{ $totalProduk }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== TABEL PRODUK ==================== --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="px-4 pt-3 pb-2 border-bottom">
            <h6 class="fw-semibold mb-0">Daftar Produk</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Foto</th>
                        <th>Nama Ikan</th>
                        <th>Stok (kg)</th>
                        <th>Harga Aktif (per kg)</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk as $index => $item)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td>
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}"
                                     alt="{{ $item->nama_ikan }}" class="foto-thumb">
                            @else
                                <div class="foto-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $item->nama_ikan }}</td>
                        <td>
                            {{ number_format($item->stok_berat, 1) }} kg
                        </td>
                        <td class="fw-semibold text-primary">
                            @if($item->harga > 0)
                                Rp {{ number_format($item->harga, 0, ',', '.') }} / kg
                            @else
                                <span class="text-muted">— belum ada harga</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.produk.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.produk.destroy', $item->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus produk {{ $item->nama_ikan }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada produk. Klik "Tambah Produk" untuk mulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== MODAL TAMBAH PRODUK ==================== --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Ikan</label>
                        <input type="text" name="nama_ikan" class="form-control"
                               placeholder="cth: Lele, Nila, Gurame" required>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Stok (kg)</label>
                            <input type="number" name="stok_berat" class="form-control"
                                   placeholder="0" step="0.01" min="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Harga Awal (Rp/kg)</label>
                            <input type="number" name="harga" class="form-control"
                                   placeholder="0" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label fw-semibold">Foto Produk</label>
                        <input type="file" name="foto" class="form-control"
                               accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-text">Opsional. Maks 2MB (jpg, png, webp)</div>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
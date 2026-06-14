{{-- resources/views/admin/edit_produk.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Produk - Admin')

@section('styles')
<style>
    .history-table th { font-size: 12px; color: #6c757d; }
    .history-table td { font-size: 13px; }
    .harga-aktif-badge {
        background: #0d6efd22; color: #0d6efd;
        font-size: 11px; padding: 2px 8px; border-radius: 20px;
    }
    .foto-preview {
        width: 120px; height: 120px;
        object-fit: cover; border-radius: 10px;
        border: 2px dashed #dee2e6;
    }
</style>
@endsection

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.dashboard_admin') }}" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
    </a>
    <h4 class="fw-bold mt-1 mb-0">Edit Produk</h4>
</div>

<div class="row g-4">

    {{-- ==================== FORM EDIT ==================== --}}
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Data Produk</h6>

                <form action="{{ route('admin.produk.update', $produk->id) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Ikan</label>
                        <input type="text" name="nama_ikan" class="form-control"
                               value="{{ old('nama_ikan', $produk->nama_ikan) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok (kg)</label>
                        <div class="input-group">
                            <input type="number" name="stok_berat" class="form-control"
                                   value="{{ old('stok_berat', $produk->stok_berat) }}"
                                   step="0.01" min="0" required>
                            <span class="input-group-text text-muted">kg</span>
                        </div>
                    </div>

                    {{-- ======= HARGA BARU (opsional) ======= --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Perbarui Harga
                            <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_baru" class="form-control"
                                   placeholder="{{ number_format($produk->harga, 0, ',', '.') }}"
                                   min="0">
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Harga aktif saat ini:
                            <strong>Rp {{ number_format($produk->harga, 0, ',', '.') }}</strong>.
                            Isi field ini hanya jika harga berubah — akan dicatat sebagai harga baru hari ini.
                        </div>
                    </div>

                    {{-- ======= FOTO ======= --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Produk</label>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            @if($produk->foto)
                                <img src="{{ asset('storage/' . $produk->foto) }}"
                                     alt="foto" class="foto-preview" id="fotoPreview">
                            @else
                                <div class="foto-preview d-flex align-items-center justify-content-center text-muted" id="fotoPreview">
                                    <i class="bi bi-image fs-3"></i>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="foto" class="form-control"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               onchange="previewFoto(this)">
                        <div class="form-text">Kosongkan jika tidak ingin mengubah foto.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.dashboard_admin') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== RIWAYAT HARGA ==================== --}}
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Harga
                </h6>

                @if($produk->historyHarga->isEmpty())
                    <p class="text-muted small">Belum ada riwayat harga.</p>
                @else
                    <table class="table table-sm history-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th class="text-end">Harga</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produk->historyHarga as $index => $h)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($h->tanggal)->isoFormat('D MMM Y') }}</td>
                                <td class="text-end fw-semibold">
                                    Rp {{ number_format($h->harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($index === 0)
                                        <span class="harga-aktif-badge">aktif</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="text-muted mb-0" style="font-size:11px">
                        <i class="bi bi-info-circle me-1"></i>
                        Harga terbaru otomatis menjadi harga aktif.
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('fotoPreview');
            preview.outerHTML = `<img src="${e.target.result}" alt="preview" class="foto-preview" id="fotoPreview">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
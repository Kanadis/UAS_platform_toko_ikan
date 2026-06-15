{{-- resources/views/profil/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Profil Saya - Toko Ikan')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="mb-4 text-dark"><i class="bi bi-person-badge"></i> Profil Saya</h3>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Akun</h5>
                    <button type="button" class="btn btn-light btn-sm text-primary fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditProfil">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Nama Lengkap</div>
                        <div class="col-sm-8">{{ $user->nama }}</div>
                    </div>
                    <hr class="text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Alamat Email</div>
                        <div class="col-sm-8">{{ $user->email }}</div>
                    </div>
                    <hr class="text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Nomor Telepon</div>
                        <div class="col-sm-8">
                            {{ $user->no_telp ?: ($alamat ? $alamat->nomor_telp_alamat : '-') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Alamat Pengiriman</h5>
                    {{-- PERUBAHAN DI SINI: Menambahkan parameter source --}}
                    <a href="{{ route('profil.alamat', ['source' => 'profil']) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> {{ $alamat ? 'Ubah Alamat' : 'Tambah Alamat' }}
                    </a>
                </div>
                <div class="card-body p-4 bg-light">
                    @if($alamat)
                        <h6 class="fw-bold mb-2">{{ $user->nama }} <span class="badge bg-secondary ms-2">{{ $alamat->nomor_telp_alamat }}</span></h6>
                        <p class="mb-1">{{ $alamat->alamat_lengkap }}</p>
                        @if($alamat->deskripsi)
                            <small class="text-muted"><i class="bi bi-sticky me-1"></i>Catatan: {{ $alamat->deskripsi }}</small>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-house-x text-muted fs-1 mb-2 d-block"></i>
                            <p class="text-muted mb-0">Anda belum mengatur alamat pengiriman.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="modal fade" id="modalEditProfil" tabindex="-1" aria-labelledby="modalEditProfilLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('profil.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fw-bold" id="modalEditProfilLabel">Edit Profil Saya</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama" value="{{ $user->nama }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Alamat Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-bold small text-muted">Nomor Telepon</label>
                                    <input type="text" class="form-control" name="no_telp" value="{{ $user->no_telp ?: ($alamat ? $alamat->nomor_telp_alamat : '') }}" placeholder="Contoh: 081234567890">
                                    <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">*Opsional. Digunakan untuk mempermudah komunikasi terkait pesanan.</small>
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 pt-0">
                                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
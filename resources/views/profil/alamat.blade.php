@extends('layouts.app')

@section('title', 'Alamat Pengiriman')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Alamat Pengiriman</div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('profil.alamat.simpan') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" class="form-control @error('alamat_lengkap') is-invalid @enderror" rows="3" required>{{ old('alamat_lengkap', $alamat->alamat_lengkap ?? '') }}</textarea>
                            @error('alamat_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon (Alternatif)</label>
                            <input type="text" name="nomor_telp_alamat" class="form-control @error('nomor_telp_alamat') is-invalid @enderror" value="{{ old('nomor_telp_alamat', $alamat->nomor_telp_alamat ?? '') }}">
                            @error('nomor_telp_alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi (opsional, misal: Rumah, Kantor)</label>
                            <input type="text" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" value="{{ old('deskripsi', $alamat->deskripsi ?? '') }}">
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                        <a href="{{ route('checkout.halaman') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
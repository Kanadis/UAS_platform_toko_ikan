{{-- resources/views/profil/alamat.blade.php --}}
@extends('layouts.app')

@section('title', 'Alamat Pengiriman - Toko Ikan')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="mb-0 text-dark">Alamat Pengiriman</h6>
                </div>
                <div class="card-body p-4 bg-white">
                    <form action="{{ route('profil.alamat.simpan') }}" method="POST">
                        @csrf
                        {{-- INPUT TERSEMBUNYI: Menyimpan jejak halaman sebelumnya --}}
                        <input type="hidden" name="source" value="{{ $source ?? 'checkout' }}">

                        <div class="mb-4">
                            <label class="form-label text-dark">Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat_lengkap" rows="4" required>{{ old('alamat_lengkap', $alamat->alamat_lengkap ?? '') }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-dark">Nomor Telepon (Alternatif)</label>
                            <input type="text" class="form-control" name="nomor_telp_alamat" value="{{ old('nomor_telp_alamat', $alamat->nomor_telp_alamat ?? '') }}">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-dark">Deskripsi (opsional, misal: Rumah, Kantor)</label>
                            <input type="text" class="form-control" name="deskripsi" value="{{ old('deskripsi', $alamat->deskripsi ?? '') }}">
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                            
                            {{-- TOMBOL KEMBALI PINTAR: Arahkan sesuai jejak asal --}}
                            <a href="{{ ($source ?? 'checkout') === 'profil' ? route('profil.index') : route('checkout.halaman') }}" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
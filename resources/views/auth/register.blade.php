{{-- resources/views/auth/register.blade.php --}}

@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="auth-card shadow-sm">
    <p class="auth-brand"><i class="bi bi-fish"></i> Toko Ikan</p>
    <p class="auth-subtitle">Buat akun baru</p>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        {{-- Nama --}}
        <div class="mb-3">
            <label class="form-label">Nama lengkap</label>
            <input
                type="text"
                name="nama"
                class="form-control @error('nama') is-invalid @enderror"
                placeholder="Masukkan nama lengkap"
                value="{{ old('nama') }}"
                required
            >
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- No. Telepon --}}
        <div class="mb-3">
            <label class="form-label">
                No. telepon
                <span class="text-secondary fw-normal">(opsional)</span>
            </label>
            <input
                type="text"
                name="no_telp"
                class="form-control @error('no_telp') is-invalid @enderror"
                placeholder="08xxxxxxxxxx"
                value="{{ old('no_telp') }}"
            >
            @error('no_telp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="contoh@email.com"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Minimal 8 karakter"
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label class="form-label">Konfirmasi password</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="Ulangi password"
                required
            >
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>

    <div class="text-center mt-3" style="font-size: 13px; color: #6c757d;">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-500">Masuk di sini</a>
    </div>
</div>
@endsection
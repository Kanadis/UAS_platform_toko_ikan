{{-- resources/views/auth/login.blade.php --}}

@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="auth-card shadow-sm">
    <p class="auth-brand"><i class="bi bi-fish"></i> Toko Ikan</p>
    <p class="auth-subtitle">Masuk ke akunmu</p>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 13px; border-radius: 8px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Pesan error login --}}
    @if ($errors->has('email'))
        <div class="alert alert-danger" style="font-size: 13px; border-radius: 8px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first('email') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

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
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Masukkan password"
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>

    <div class="text-center mt-3" style="font-size: 13px; color: #6c757d;">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-500">Daftar sekarang</a>
    </div>
</div>
@endsection
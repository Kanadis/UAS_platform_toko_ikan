<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==================== REGISTER ====================

    // Tampilkan form register
    public function showRegister()
    {
        return view('auth.registrasi');
    }

    // Proses register
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama'     => 'required|string|max:255',
            'no_telp'  => 'nullable|string|max:20',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed', // confirmed = harus ada field password_confirmation
        ], [
            'nama.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Simpan user baru
        $user = User::create([
            'nama'     => $request->nama,
            'no_telp'  => $request->no_telp,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pembeli', // default selalu pembeli
        ]);

        // Buat keranjang otomatis untuk user baru
        Keranjang::create([
            'user_id' => $user->id,
        ]);

        // Langsung login setelah register
        Auth::login($user);

        return redirect()->route('produk.index')->with('success', 'Akun berhasil dibuat!');
    }

    // ==================== LOGIN ====================

    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Coba login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Kalau admin, redirect ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Kalau pembeli, redirect ke halaman produk
            return redirect()->route('produk.index')->with('success', 'Selamat datang, ' . Auth::user()->nama . '!');
        }

        // Kalau gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    // ==================== LOGOUT ====================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar.');
    }
}
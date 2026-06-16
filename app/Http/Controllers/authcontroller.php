<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }
    
    // Proses login (hapus remember me)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Coba login dengan email dan password (tanpa remember)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard_admin');
            }
            return redirect()->intended('/beranda');
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
    
    // Menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }
    
    // Proses register
    public function register(Request $request)
    {
        // 🛡️ VALIDASI KETAT REGEX 🛡️
        $request->validate([
            'nama' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'no_telp' => ['nullable', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
            // Tambahkan regex untuk memastikan email diawali huruf/angka
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9]/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'no_telp.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'no_telp.min' => 'Nomor telepon minimal 10 angka.',
            'no_telp.max' => 'Nomor telepon maksimal 15 angka.',
            'email.email' => 'Format email tidak valid (harus menggunakan @ dan domain asli).',
            'email.regex' => 'Email harus diawali dengan huruf atau angka.' // Pesan error baru
        ]);
        
        $user = User::create([
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pembeli',
        ]);
        
        // Auto login setelah register
        Auth::login($user);
        
        return redirect('/beranda')->with('success', 'Selamat datang, ' . $request->nama . '!');
    }
    
    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Anda telah keluar.');
    }
}
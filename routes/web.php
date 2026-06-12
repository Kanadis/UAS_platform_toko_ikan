<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== AUTH (Tanpa Login) ====================

// Halaman login & register hanya bisa diakses jika belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (harus sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== SEMUA HALAMAN HARUS LOGIN ====================

Route::middleware('auth')->group(function () {
    
    // Halaman Beranda (harus login)
    Route::get('/', [ProdukController::class, 'index'])->name('beranda');
    Route::get('/beranda', [ProdukController::class, 'index'])->name('beranda');
    
    // Halaman Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');
    
    // Halaman Lainnya
    Route::get('/riwayat', function () {
        return view('riwayat');
    })->name('riwayat');
    
    Route::get('/keranjang', function () {
        return view('keranjang');
    })->name('keranjang');
    
    Route::get('/akun', function () {
        return view('akun');
    })->name('akun');
});
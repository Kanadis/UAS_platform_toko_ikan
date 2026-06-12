<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== AUTH ====================

// Kalau sudah login, tidak bisa akses halaman login/register lagi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (harus sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== PRODUK (dengan auth) ====================

// Hanya bisa diakses kalau sudah login
Route::middleware('auth')->group(function () {
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');
});

// ==================== BERANDA (tanpa auth - akses bebas) ====================

// Route untuk halaman beranda tanpa login
Route::get('/', [ProdukController::class, 'index'])->name('beranda');
Route::get('/beranda', [ProdukController::class, 'index'])->name('beranda');

// ==================== REDIRECT (dari root ke produk) ====================
// Catatan: Baris ini dinonaktifkan karena root sudah指向 beranda
// Route::get('/', function () {
//     return redirect()->route('produk.index');
// });

// ==================== HALAMAN STATIS (opsional) ====================

Route::get('/riwayat', function () {
    return view('riwayat');
})->name('riwayat');

Route::get('/keranjang', function () {
    return view('keranjang');
})->name('keranjang');

Route::get('/akun', function () {
    return view('akun');
})->name('akun');
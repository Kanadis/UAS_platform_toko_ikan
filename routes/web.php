<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RiwayatController;
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
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
    

    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
    
    // Profil & Alamat
    Route::get('/profil', [App\Http\Controllers\ProfilController::class, 'index'])->name('profil.index'); 
    Route::get('/profil/alamat', [App\Http\Controllers\ProfilController::class, 'formAlamat'])->name('profil.alamat');
    Route::post('/profil/alamat', [App\Http\Controllers\ProfilController::class, 'simpanAlamat'])->name('profil.alamat.simpan');
    Route::get('/profil', [App\Http\Controllers\ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/update', [App\Http\Controllers\ProfilController::class, 'updateProfil'])->name('profil.update'); 


    Route::get('/akun', function () {
        return view('akun');
    })->name('akun');

});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Route untuk manajemen pesanan (tambahkan ini)
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('order.update-status');

    Route::get('/dashboard_admin', [AdminProdukController::class, 'dashboard'])->name('dashboard_admin');
    Route::post('/produk', [AdminProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [AdminProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [AdminProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [AdminProdukController::class, 'destroy'])->name('produk.destroy');
    
});

// ==================== FITUR KERANJANG ====================
Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');

// Rute untuk menghapus produk dari keranjang
Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

// Rute untuk menampilkan halaman desain Checkout
Route::get('/checkout', [App\Http\Controllers\TransaksiController::class, 'halamanCheckout'])->name('checkout.halaman');

// Rute untuk memproses data saat tombol "Buat Pesanan" ditekan
Route::post('/checkout/proses', [App\Http\Controllers\TransaksiController::class, 'prosesCheckout'])->name('checkout.proses');

// Rute untuk menampilkan halaman instruksi pembayaran setelah checkout
Route::get('/checkout/instruksi', [App\Http\Controllers\TransaksiController::class, 'instruksi'])->name('checkout.instruksi');

// Rute untuk melihat status pesanan yang sedang aktif
Route::get('/status-pesanan', [App\Http\Controllers\TransaksiController::class, 'statusPesanan'])->name('status.pesanan');

// Rute untuk membatalkan transaksi yang sedang berjalan
Route::post('/status-pesanan/batal/{id}', [App\Http\Controllers\TransaksiController::class, 'batal'])->name('transaksi.batal');

// Rute untuk simulasi pelunasan pembayaran
Route::post('/status-pesanan/bayar/{id}', [App\Http\Controllers\TransaksiController::class, 'bayar'])->name('transaksi.bayar');
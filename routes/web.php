<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/detaiLproduk', function () {
    return view('detail_produk');
});

Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/registrasi', function () {
    return view('auth.registrasi');
});

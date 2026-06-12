<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Halaman daftar semua produk (dashboard/beranda)
    public function index()
    {
        $produk = Produk::with('hargaTerbaru')
                        ->where('stok_berat', '>', 0) // hanya tampilkan yang stok masih ada
                        ->get();

        return view('produk.index', compact('produk'));
    }

    // Halaman detail satu produk
    public function show($id)
    {
        // Ambil produk, kalau tidak ditemukan otomatis 404
        $produk = Produk::findOrFail($id);

        // Ambil harga terbaru dari history_harga
        $harga = $produk->hargaTerbaru;

        // Kalau produk belum punya harga sama sekali, lempar 404
        abort_if(!$harga, 404, 'Harga produk belum tersedia.');

        return view('produk.detail', compact('produk', 'harga'));
    }
}
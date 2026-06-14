<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
{
    $search = $request->get('search');
    $produk = Produk::query();
    if ($search) {
        $produk->where('nama_ikan', 'like', '%' . $search . '%');
    }
    $produk = $produk->orderBy('nama_ikan')->get();

    // Jika tidak pakai jenis, kirim semua produk ke view
    return view('menu_dashboard', compact('produk', 'search'));
}
    
    public function show($id)
    {
        $produk = Produk::with('historyHarga')->findOrFail($id);
        return view('produk_detail', compact('produk'));
    }
}
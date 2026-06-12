<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jenis = $request->get('jenis');
        
        // Query produk
        $query = Produk::with('hargaAktif');
        
        // Filter berdasarkan jenis_ikan (konsumsi atau bibit)
        if ($jenis && $jenis != '') {
            if ($jenis == 'konsumsi') {
                $query->where('jenis_ikan', 'like', '%konsumsi%');
            } elseif ($jenis == 'bibit') {
                $query->where('jenis_ikan', 'like', '%bibit%');
            }
        }
        
        // Filter berdasarkan pencarian nama
        if ($search) {
            $query->where('nama_ikan', 'like', '%' . $search . '%');
        }
        
        // Ambil data
        $produk = $query->get();
        
        // Kelompokkan berdasarkan jenis
        $produkKonsumsi = $produk->filter(function($item) {
            return stripos($item->jenis_ikan, 'konsumsi') !== false;
        });
        
        $produkBibit = $produk->filter(function($item) {
            return stripos($item->jenis_ikan, 'bibit') !== false;
        });
        
        return view('menu_dashboard', compact('produkKonsumsi', 'produkBibit', 'search', 'jenis'));
    }
    
    public function show($id)
    {
        $produk = Produk::with('historyHarga')->findOrFail($id);
        return view('produk_detail', compact('produk'));
    }
}
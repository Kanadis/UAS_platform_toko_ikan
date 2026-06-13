<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // === FUNGSI INDEX: Untuk menampilkan halaman keranjang ===
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data keranjang beserta detail produknya khusus untuk user yang sedang login
        $keranjang = Keranjang::with(['DetailKeranjang.Produk'])
                              ->where('user_id', $user->id)
                              ->first();

        return view('keranjang.keranjang', compact('keranjang'));
    }

    // === FUNGSI TAMBAH: Untuk memproses tombol Pesan Sekarang ===
    public function tambah(Request $request, $id)
    {
        // 1. Pastikan user sudah login (hanya pembeli terdaftar yang bisa pesan)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk memesan.');
        }

        $user = Auth::user();
        
        // 2. Pastikan produk yang mau dibeli itu ada
        $produk = Produk::findOrFail($id);

        // 3. Cari keranjang milik user ini, kalau belum ada sistem otomatis membuatkan
        $keranjang = Keranjang::firstOrCreate(
            ['user_id' => $user->id],
            ['tanggal_dibuat' => now()]
        );

        // 4. Cek apakah ikan ini sudah ada di dalam keranjang?
        $detail = DetailKeranjang::where('keranjang_id', $keranjang->id)
                                 ->where('produk_id', $produk->id)
                                 ->first();

        // Default jumlah beli adalah 1 (bisa diubah nanti kalau ada form input jumlah)
        $jumlahBeli = $request->input('jumlah', 1);

        if ($detail) {
            // Jika ikan sudah ada, cukup tambahkan jumlahnya agar tidak dobel/error
            $detail->jumlah += $jumlahBeli;
            $detail->save();
        } else {
            // Jika ikan belum ada, masukkan sebagai baris baru
            DetailKeranjang::create([
                'keranjang_id' => $keranjang->id,
                'produk_id' => $produk->id,
                'jumlah' => $jumlahBeli,
            ]);
        }

        return redirect()->back()->with('success', $produk->nama_ikan . ' berhasil ditambahkan ke keranjang!');
    }

    // === FUNGSI HAPUS: Untuk membuang produk dari keranjang ===
    public function hapus($id)
    {
        // Cari data detail keranjang berdasarkan ID-nya
        $detail = DetailKeranjang::findOrFail($id);
        
        // Hapus data tersebut dari database
        $detail->delete();

        // Kembalikan ke halaman keranjang dengan pesan sukses
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}
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
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk memesan.');
        }

        $user = Auth::user();

        // 🛡️ ERROR HANDLING 1: CEK ROLE ADMIN 🛡️
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Admin tidak diperbolehkan melakukan pemesanan.');
        }
        
        // 2. Pastikan produk yang mau dibeli itu ada
        $produk = Produk::findOrFail($id);

        // 3. Cari keranjang milik user ini
        $keranjang = Keranjang::firstOrCreate(
            ['user_id' => $user->id],
            ['tanggal_dibuat' => now()]
        );

        // 4. Cek apakah ikan ini sudah ada di dalam keranjang?
        $detail = DetailKeranjang::where('keranjang_id', $keranjang->id)
                                 ->where('produk_id', $produk->id)
                                 ->first();

        // Mengambil jumlah yang baru diinput pembeli
        $jumlahBeli = (float) $request->input('jumlah', 0);

        // =========================================================
        // 🛡️ ERROR HANDLING 2: VALIDASI INPUT KOSONG / MINUS / NOL 🛡️
        // =========================================================
        if ($jumlahBeli < 0.1) {
            return redirect()->back()->with('error', 'Gagal! Jumlah pesanan tidak valid. Minimal pembelian adalah 0.1 kg.');
        }

        // =========================================================
        // 🛡️ ERROR HANDLING 3: VALIDASI STOK KERANJANG VS GUDANG 🛡️
        // =========================================================
        $totalDiminta = $jumlahBeli;
        if ($detail) {
            $totalDiminta += $detail->jumlah;
        }

        if ($totalDiminta > $produk->stok_berat) {
            
            $sisaBisaDipesan = $produk->stok_berat - ($detail ? $detail->jumlah : 0);
            
            if ($detail) {
                $pesanError = 'Gagal! Anda sudah memiliki ' . $detail->jumlah . ' kg ' . $produk->nama_ikan . ' di keranjang. Sisa stok yang bisa ditambahkan hanya ' . $sisaBisaDipesan . ' kg lagi.';
            } else {
                $pesanError = 'Gagal! Stok ' . $produk->nama_ikan . ' tidak mencukupi.';
            }
            
            return redirect()->back()->with('error', $pesanError);
        }
        // =========================================================

        // Jika lolos semua validasi, lanjutkan proses simpan
        if ($detail) {
            // Jika ikan sudah ada, tambahkan jumlah desimalnya
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
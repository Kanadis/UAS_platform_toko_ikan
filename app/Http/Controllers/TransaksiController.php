<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // === 1. FUNGSI MENAMPILKAN HALAMAN CHECKOUT ===
    public function halamanCheckout()
    {
        $user = Auth::user();
        $keranjang = Keranjang::with('DetailKeranjang.Produk')->where('user_id', $user->id)->first();

        // Ambil alamat dari database menggunakan Query Builder
        $alamat = DB::table('alamat')->where('user_id', $user->id)->first();

        if (!$keranjang || $keranjang->DetailKeranjang->count() == 0) {
            return redirect()->route('keranjang')->with('error', 'Keranjang Anda kosong!');
        }

        return view('transaksi.checkout', compact('keranjang', 'alamat'));
    }

    // === 2. FUNGSI MEMPROSES PESANAN (TOMBOL "BUAT PESANAN") ===
    public function prosesCheckout(Request $request)
    {
        $user = Auth::user();
        $keranjang = Keranjang::with('DetailKeranjang.Produk')->where('user_id', $user->id)->first();

        if (!$keranjang || $keranjang->DetailKeranjang->count() == 0) {
            return redirect()->route('keranjang')->with('error', 'Keranjang Anda kosong!');
        }

        $totalHarga = 0;
        foreach ($keranjang->DetailKeranjang as $detail) {
            $totalHarga += $detail->Produk->harga * $detail->jumlah;
        }

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'alamat_id' => 1, 
                'tanggal_transaksi' => now(),
                'total_harga' => $totalHarga,
                'status' => 'menunggu_pembayaran',
            ]);

            foreach ($keranjang->DetailKeranjang as $detail) {
                $produk = $detail->Produk;
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk->id,
                    'jumlah' => $detail->jumlah,
                    'harga_saat_beli' => $produk->harga,
                    'subtotal' => $produk->harga * $detail->jumlah,
                ]);

                if ($produk->stok_berat !== null) {
                    $produk->stok_berat -= $detail->jumlah;
                    $produk->save();
                }
            }

            $keranjang->DetailKeranjang()->delete();
            DB::commit();

            $metodePembayaran = $request->metode_pembayaran;

            return redirect()->route('checkout.instruksi')->with([
                'success' => 'Pesanan berhasil dibuat!',
                'metode' => $metodePembayaran,
                'total_harga' => $totalHarga,
                'order_id' => 'INV-' . date('Ymd') . '-' . $transaksi->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // === 3. FUNGSI MENAMPILKAN HALAMAN INSTRUKSI PEMBAYARAN ===
    public function instruksi()
    {
        if (!session('metode')) {
            return redirect()->route('beranda');
        }

        return view('transaksi.instruksi');
    }
    
    // === 4. FUNGSI STATUS PESANAN (PESANAN AKTIF) ===
    public function statusPesanan()
    {
        $user = Auth::user();
        $transaksi = Transaksi::with(['detailTransaksi.produk'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu_pembayaran', 'diproses', 'dikirim'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transaksi.status', compact('transaksi'));
    }

    // === 5. FUNGSI RIWAYAT (PESANAN SELESAI/BATAL) ===
    public function riwayat()
    {
        $user = Auth::user();
        $transaksi = Transaksi::with(['detailTransaksi.produk'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riwayat', compact('transaksi'));
    }

    // === 6. FUNGSI BATALKAN PESANAN (MENGEMBALIKAN STOK) ===
    public function batal($id)
    {
        $user = Auth::user();
        
        // Cari transaksi khusus milik user yang sedang login dan yang statusnya masih menunggu pembayaran
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'menunggu_pembayaran')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Loop semua produk di dalam transaksi ini untuk mengembalikan stoknya
            foreach ($transaksi->detailTransaksi as $detail) {
                $produk = $detail->produk;
                if ($produk && $produk->stok_berat !== null) {
                    $produk->stok_berat += $detail->jumlah; // Stok ditambah kembali
                    $produk->save();
                }
            }

            // Ubah status nota menjadi dibatalkan
            $transaksi->status = 'dibatalkan';
            $transaksi->save();

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan. Stok produk telah dikembalikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
    // === 7. FUNGSI UPLOAD BUKTI BAYAR ===
    public function bayar(Request $request, $id)
    {
        // 1. Validasi input: Wajib pilih metode & wajib upload gambar max 2MB
        $request->validate([
            'metode_pembayaran' => 'required|in:transfer_bank,cod,ewallet',
            'bukti_pembayaran'  => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah!',
            'bukti_pembayaran.image' => 'File harus berupa gambar (JPG/PNG).',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB.'
        ]);

        $user = Auth::user();
        
        // Cari transaksi
        $transaksi = Transaksi::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'menunggu_pembayaran')
            ->firstOrFail();

        // 2. Simpan gambar ke folder "storage/app/public/bukti_pembayaran"
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // 3. Catat di tabel Pembayaran (Status otomatis default 'belum_bayar' menunggu admin)
        \App\Models\Pembayaran::create([
            'transaksi_id' => $transaksi->id,
            'status_pembayaran' => 'belum_bayar', 
            'tanggal_bayar' => now(),
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $path,
        ]);

        // 4. Ubah status transaksi utama agar pindah antrean
        $transaksi->status = 'diproses';
        $transaksi->save();

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil dikirim! Mohon tunggu konfirmasi dari Admin Toko.');
    }
}
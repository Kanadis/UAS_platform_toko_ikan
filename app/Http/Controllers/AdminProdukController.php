<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\HistoryHarga;  // JANGAN LUPA import model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProdukController extends Controller
{
    // Tampilkan dashboard admin
    public function dashboard()
    {
        $produk = Produk::with('hargaAktif')->orderBy('created_at', 'desc')->get();
        $totalProduk = Produk::count();
        return view('admin.dashboard_admin', compact('produk', 'totalProduk'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'stok_berat' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk', 'public');
        }

        // Buat produk TERLEBIH DAHULU (sehingga dapat id)
        $produk = Produk::create([
            'nama_ikan' => $request->nama_ikan,
            'stok_berat' => $request->stok_berat,
            'foto' => $fotoPath,
        ]);

        // Kemudian simpan harga awal ke history_harga
        HistoryHarga::create([
            'produk_id' => $produk->id,   // gunakan $produk->id (bukan $produk)
            'harga' => $request->harga,
            'tanggal' => now(),
        ]);

        return redirect()->route('admin.dashboard_admin')->with('success', 'Produk ditambahkan.');
    }

    // Edit produk (tampilkan form)
    public function edit($id)
    {
        $produk = Produk::with('historyHarga')->findOrFail($id);
        return view('admin.edit_produk', compact('produk'));
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'stok_berat' => 'required|numeric|min:0',
            'harga_baru' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Update data dasar
        $produk->nama_ikan = $request->nama_ikan;
        $produk->stok_berat = $request->stok_berat;

        // Update foto jika ada
        if ($request->hasFile('foto')) {
            if ($produk->foto) Storage::disk('public')->delete($produk->foto);
            $produk->foto = $request->file('foto')->store('produk', 'public');
        }
        $produk->save();

        // Jika ada harga_baru, simpan ke history_harga (BUKAN ke kolom harga produk)
        if ($request->filled('harga_baru') && $request->harga_baru > 0) {
            // Cek apakah sudah ada history untuk tanggal hari ini
            $history = HistoryHarga::where('produk_id', $produk->id)
                        ->whereDate('tanggal', now()->toDateString())
                        ->first();

            if ($history) {
                // Update harga yang sudah ada
                $history->update([
                    'harga' => $request->harga_baru,
                ]);
            } else {
                // Buat baru
                HistoryHarga::create([
                    'produk_id' => $produk->id,
                    'harga' => $request->harga_baru,
                    'tanggal' => now(),
                ]);
            }
        }

        return redirect()->route('admin.dashboard_admin')->with('success', 'Produk diperbarui.');
    }

    // Hapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        // Hapus foto jika ada
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }
        
        // Hapus history harga
        $produk->historyHarga()->delete();
        
        // Hapus detail transaksi (foreign key constraint)
        $produk->detailTransaksi()->delete();
        
        // Hapus produk
        $produk->delete();

        return redirect()->route('admin.dashboard_admin')->with('success', 'Produk dihapus.');
    }
}
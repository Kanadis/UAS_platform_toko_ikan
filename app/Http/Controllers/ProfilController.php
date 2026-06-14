<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    /**
     * Menampilkan form alamat (untuk pertama kali atau edit)
     */
    public function formAlamat()
    {
        $alamat = Alamat::where('user_id', Auth::id())->first();
        return view('profil.alamat', compact('alamat'));
    }

    /**
     * Menyimpan atau update alamat user
     */
    public function simpanAlamat(Request $request)
    {
        $request->validate([
            'alamat_lengkap' => 'required|string|max:500',
            'nomor_telp_alamat' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        Alamat::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'alamat_lengkap' => $request->alamat_lengkap,
                'nomor_telp_alamat' => $request->nomor_telp_alamat,
                'deskripsi' => $request->deskripsi,
            ]
        );

        // Redirect ke checkout atau ke halaman sebelumnya
        return redirect()->route('checkout.halaman')->with('success', 'Alamat berhasil disimpan.');
    }

    // Bisa tambahkan method lain jika diperlukan: editProfil, updateProfil, dll.
}
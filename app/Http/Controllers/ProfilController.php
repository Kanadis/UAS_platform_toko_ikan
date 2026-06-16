<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $alamat = Alamat::where('user_id', $user->id)->first();
        return view('profil.index', compact('user', 'alamat'));
    }

    public function updateProfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // 🛡️ VALIDASI KETAT REGEX 🛡️
        $request->validate([
            // Nama hanya boleh huruf dan spasi
            'nama' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            // Email harus format RFC/DNS valid dan diawali huruf/angka
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email,' . $user->id, 'regex:/^[a-zA-Z0-9]/'],
            // Telepon hanya boleh angka murni, minimal 10 maksimal 15 digit
            'no_telp' => ['nullable', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:15'], 
        ], [
            // Pesan error kustom
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'no_telp.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'no_telp.min' => 'Nomor telepon minimal 10 angka.',
            'no_telp.max' => 'Nomor telepon maksimal 15 angka.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Email harus diawali dengan huruf atau angka.'
        ]);

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->no_telp = $request->no_telp;
        $user->save();

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function formAlamat(Request $request)
    {
        $alamat = Alamat::where('user_id', Auth::id())->first();
        
        // Tangkap jejak navigasi. Default ke 'checkout' agar fitur tim tetap aman.
        $source = $request->query('source', 'checkout'); 
        
        return view('profil.alamat', compact('alamat', 'source'));
    }

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

        // LOGIKA PINTAR: Arahkan sesuai jejak asal user
        if ($request->source === 'profil') {
            return redirect()->route('profil.index')->with('success', 'Alamat berhasil disimpan.');
        }

        // Jika bukan dari profil (atau dari keranjang), tetap arahkan ke checkout (Fitur Tim Aman)
        return redirect()->route('checkout.halaman')->with('success', 'Alamat berhasil disimpan.');
    }
}
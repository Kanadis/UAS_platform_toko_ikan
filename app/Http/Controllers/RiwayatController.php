<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Status yang ingin ditampilkan (sesuaikan dengan enum di database Anda)
        $statuses = ['diproses', 'dikirim', 'selesai', 'dibatalkan'];

        $riwayat = Transaksi::where('user_id', $user->id)
                            ->whereIn('status', $statuses)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('riwayat.riwayat', compact('riwayat'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi.
     */
    public function index()
    {
        $transactions = Transaksi::with('user')->latest()->paginate(10);
        return view('order.index', compact('transactions'))->with('debug', 'OK');
    }

    /**
     * Menampilkan detail dari satu transaksi.
     */
    public function show($id)
    {
        $transaction = Transaksi::with('DetailTransaksi.produk')->findOrFail($id);

        return view('order.show', compact('transaction'));
    }

    /**
     * Memproses permintaan perubahan status transaksi dari admin.
     */
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:menunggu_pembayaran,diproses,dikirim,selesai,dibatalkan',
    ]);

    $transaction = Transaksi::findOrFail($id);
    $oldStatus = $transaction->status;
    $newStatus = $request->status;

    DB::transaction(function () use ($transaction, $newStatus, $oldStatus) {
        // Jika status berubah menjadi 'diproses' (atau yang menandakan konfirmasi)
        if ($oldStatus === 'menunggu_pembayaran' && $newStatus === 'diproses') {
            foreach ($transaction->detailTransaksi as $detail) {
                $produk = $detail->produk;
                $produk->stok_berat -= $detail->jumlah;
                $produk->save();
            }
        }

        $transaction->status = $newStatus;
        $transaction->save();
    });

    return redirect()->route('admin.order.index')->with('success', 'Status pesanan diperbarui.');
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'nama_ikan',
        'jenis_ikan',
        'stok_berat',
        'foto',
    ];

    // Produk punya banyak history harga
    public function HistoryHarga()
    {
        return $this->hasMany(HistoryHarga::class);
    }

    // Ambil harga terbaru produk ini
    public function hargaTerbaru()
    {
        return $this->hasOne(HistoryHarga::class)
                    ->latestOfMany('tanggal');
    }

    // Produk ada di banyak detail keranjang
    public function detailKeranjang()
    {
        return $this->hasMany(DetailKeranjang::class);
    }

    /* Produk ada di banyak detail transaksi
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
    */
}

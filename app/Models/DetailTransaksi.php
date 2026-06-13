<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga_saat_beli',
        'subtotal',
    ];

    // Relasi: Detail ini milik satu transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi: Detail ini merujuk ke satu produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
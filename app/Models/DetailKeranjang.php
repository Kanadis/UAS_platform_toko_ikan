<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKeranjang extends Model
{
    protected $table = 'detail_keranjang';

    protected $fillable = [
        'keranjang_id',
        'produk_id',
        'jumlah',
    ];

    // Detail keranjang milik satu keranjang
    public function Keranjang()
    {
        return $this->belongsTo(Keranjang::class);
    }

    // Detail keranjang milik satu produk
    public function Produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
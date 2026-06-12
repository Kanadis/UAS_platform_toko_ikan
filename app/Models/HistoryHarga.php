<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryHarga extends Model
{
    protected $table = 'history_harga';

    protected $fillable = [
        'produk_id',
        'tanggal',
        'harga',
    ];

    // History harga milik satu produk
    public function Produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
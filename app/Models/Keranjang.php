<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';

    protected $fillable = [
        'user_id',
        'tanggal_dibuat',
    ];

    // Keranjang milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Keranjang punya banyak detail keranjang
    public function DetailKeranjang()
    {
        return $this->hasMany(DetailKeranjang::class);
    }

    // Produk yang ada di keranjang (lewat detail_keranjang)
    public function Produk()
    {
        return $this->belongsToMany(Produk::class, 'detail_keranjang')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }
}
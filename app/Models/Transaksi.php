<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'user_id',
        'alamat_id',
        'tanggal_transaksi',
        'total_harga',
        'status',
    ];

    // Relasi: Transaksi milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Transaksi memiliki banyak detail
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
}
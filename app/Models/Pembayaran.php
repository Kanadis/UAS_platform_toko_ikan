<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    
    // Kolom apa saja yang boleh diisi
    protected $fillable = [
        'transaksi_id', 
        'status_pembayaran', 
        'tanggal_bayar', 
        'metode_pembayaran', 
        'bukti_pembayaran'
    ];

    // Relasi balik ke Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryHarga extends Model
{
    use HasFactory;

    protected $table = 'history_harga';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'produk_id', 
        'tanggal', 
        'harga'
    ];

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
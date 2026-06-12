<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nama_ikan', 
        'jenis_ikan', 
        'stok_berat', 
        'foto'
    ];

    // Relasi ke history_harga
    public function historyHarga()
    {
        return $this->hasMany(HistoryHarga::class, 'produk_id', 'id');
    }

    // Ambil harga aktif terbaru (berdasarkan tanggal)
    public function hargaAktif()
    {
        return $this->hasOne(HistoryHarga::class, 'produk_id', 'id')
                    ->where('tanggal', '<=', date('Y-m-d'))
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    // Accessor untuk mendapatkan harga terbaru
    public function getHargaAttribute()
    {
        $harga = $this->hargaAktif()->first();
        return $harga ? $harga->harga : 0;
    }
    
    // Accessor untuk mendapatkan satuan (dari jenis_ikan)
    public function getSatuanAttribute()
    {
        // Menentukan satuan berdasarkan jenis_ikan
        if (strpos(strtolower($this->jenis_ikan), 'bibit') !== false) {
            return 'ekor';
        }
        return 'kg';
    }
    
    // Accessor untuk mendapatkan keterangan (dari stok_berat)
    public function getKeteranganAttribute()
    {
        if ($this->satuan == 'kg') {
            return $this->stok_berat . ' kg tersedia';
        } else {
            return $this->stok_berat . ' ekor tersedia';
        }
    }
}
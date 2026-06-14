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
        'stok_berat', 
        'foto'
        // 'jenis_ikan' dihapus
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
    
    // Accessor untuk mendapatkan satuan (selalu kg karena tidak ada jenis)
    public function getSatuanAttribute()
    {
        return 'kg'; // atau jika ingin dinamis nanti, bisa dari kolom lain
    }
    
    // Accessor untuk mendapatkan keterangan
    public function getKeteranganAttribute()
    {
        return $this->stok_berat . ' kg tersedia';
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
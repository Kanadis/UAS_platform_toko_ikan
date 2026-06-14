<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat';
    protected $fillable = ['user_id', 'alamat_lengkap', 'deskripsi', 'nomor_telp_alamat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
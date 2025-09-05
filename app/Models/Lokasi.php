<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lokasi', 
        'kode_lokasi',
        'alamat',
        'latitude', 
        'longitude', 
        'radius',
        'zona_waktu',
        'jam_masuk',
        'jam_keluar'
    ];

    // Relasi self join
    public function lokasi()
    {
        return $this->hasMany(Lokasi::class, 'lokasi_id');
    }

}

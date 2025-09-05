<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $fillable = [
        'nip',
        'nama_pegawai',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'id_jabatan',
        'id_lokasi',
        'foto',
    ];

    /**
     * Relasi ke tabel jabatans
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    /**
     * Relasi ke tabel lokasis
     */
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_pegawai', 'id');
    }

}

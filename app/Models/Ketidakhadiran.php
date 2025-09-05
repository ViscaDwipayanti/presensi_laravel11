<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ketidakhadiran extends Model
{
    protected $fillable = [
    'id_user',
    'tanggal_mulai',
    'tanggal_selesai',
    'keterangan',
    'lampiran',
    'status',
    'alasan_penolakan'
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}

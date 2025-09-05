<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jabatan extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama model dan tabel sudah sesuai konvensi)
    protected $table = 'jabatans';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = ['nama_jabatan'];
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pegawais')->insert([
            [
                'nip' => 'PEG-001',
                'nama_pegawai' => 'adminprezy',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Denpasar',
                'no_hp' => '081209678657',
                'id_jabatan' => 1,
                'id_lokasi' => 1,
                'foto' => 'admin.jpg'
            ],
            [
                'nip' => 'PEG-002',
                'nama_pegawai' => 'Budi Pratama',
                'jenis_kelamin' => 'Laki',
                'alamat' => 'Tabanan',
                'no_hp' => '081209678123',
                'id_jabatan' => 2,
                'id_lokasi' => 1,
                'foto' => 'budi.jpg'
            ]
        ]);
    }
}

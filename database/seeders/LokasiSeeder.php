<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lokasis')->insert([
            [
                'kode_lokasi' => 'LOC-001',
                'nama_lokasi' => 'Kantor Pusat',
                'alamat' => 'Jimbaran,Kec. Kuta Sel., Kabupaten Badung, Bali',
                'latitude' => '-8.800833',
                'longitude' => '115.165743',
                'radius' => 50,
                'zona_waktu' => 'WITA',
                'jam_masuk' => '07:30',
                'jam_keluar' => '17:00'
                
            ],
            [
                'kode_lokasi' => 'LOC-002',
                'nama_lokasi' => 'Kantor Cabang',
                'alamat' => 'Jl.Batukaru, Penebel, Tabanan',
                'latitude' => '-8.3913567',
                'longitude' => '115.1113193',
                'radius' => 50,
                'zona_waktu' => 'WITA',
                'jam_masuk' => '07:30',
                'jam_keluar' => '17:00'
            ]
        ]);
    }
}

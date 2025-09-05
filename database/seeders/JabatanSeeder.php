<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('jabatans')->insert([
        //     [
        //         'id' => 1,
        //         'nama_jabatan' => 'Admin'
        //     ],
        //     [
        //         'id' => 2,
        //         'nama_jabatan' => 'Marketing'
        //     ]
        // ]);


        Jabatan::factory()->count(10)->create();
    }
}

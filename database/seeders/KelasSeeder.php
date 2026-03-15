<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
                'nama_kelas'  => 'Matematika Dasar',
                'harga_kelas' => 150000.00,
                'hari_kelas'  => 'Senin',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nama_kelas'  => 'Bahasa Inggris',
                'harga_kelas' => 175000.00,
                'hari_kelas'  => 'Selasa',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nama_kelas'  => 'IPA Terpadu',
                'harga_kelas' => 200000.00,
                'hari_kelas'  => 'Rabu',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nama_kelas'  => 'Bahasa Indonesia',
                'harga_kelas' => 150000.00,
                'hari_kelas'  => 'Kamis',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nama_kelas'  => 'Matematika Lanjut',
                'harga_kelas' => 225000.00,
                'hari_kelas'  => 'Jumat',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
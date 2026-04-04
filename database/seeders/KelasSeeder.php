<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        Kelas::insert([
            [
                'nama_kelas'  => 'Piano Dasar',
                'harga_kelas' => 250000,
                'jam_mulai'   => '08:00',
                'jam_selesai' => '09:00',
                'hari_kelas'  => 'Senin',
                'guru_id'     => 1,
                'deskripsi'   => 'Kelas piano untuk pemula',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nama_kelas'  => 'Gitar Intermediate',
                'harga_kelas' => 300000,
                'jam_mulai'   => '10:00',
                'jam_selesai' => '11:30',
                'hari_kelas'  => 'Rabu',
                'guru_id'     => 2,
                'deskripsi'   => 'Kelas gitar tingkat menengah',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nama_kelas'  => 'Vokal Lanjutan',
                'harga_kelas' => 350000,
                'jam_mulai'   => '13:00',
                'jam_selesai' => '14:30',
                'hari_kelas'  => 'Jumat',
                'guru_id'     => 3,
                'deskripsi'   => 'Kelas vokal tingkat lanjut',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
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
                'nama_kelas'  => 'UI/UX Design',
                'harga_kelas' => 250000,
                'jam_mulai'   => '08:00',
                'jam_selesai' => '09:00',
                'hari_kelas'  => 'Senin',
                'guru_id'     => 1,
                'deskripsi'   => 'Pelajari dasar-dasar UI/UX design seperti prototyping, wireframing, dan prototyping.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nama_kelas'  => 'Robotika Dasar',
                'harga_kelas' => 300000,
                'jam_mulai'   => '10:00',
                'jam_selesai' => '11:30',
                'hari_kelas'  => 'Rabu',
                'guru_id'     => 2,
                'deskripsi'   => 'Pelajari dasar-dasar robotika seperti robotika berorientasi objek, robotika berbasis objek, dan robotika berbasis web.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nama_kelas'  => 'Pemrograman Dasar',
                'harga_kelas' => 350000,
                'jam_mulai'   => '13:00',
                'jam_selesai' => '14:30',
                'hari_kelas'  => 'Jumat',
                'guru_id'     => 3,
                'deskripsi'   => 'Pelajari dasar-dasar pemrograman seperti pemrograman berorientasi objek, pemrograman berbasis objek, dan pemrograman berbasis web.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
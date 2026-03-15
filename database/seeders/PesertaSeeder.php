<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('peserta')->insert([
            [
                'nama'          => 'Budi Santoso',
                'no_hp'         => '081234567801',
                'email'         => 'budi@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'kelas_akademik'=> '7',
                'nama_ortu'     => 'Slamet Santoso',
                'no_ortu'       => '081234560001',
                'status'        => 'aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'Dewi Rahayu',
                'no_hp'         => '081234567802',
                'email'         => 'dewi@gmail.com',
                'jenis_kelamin' => 'perempuan',
                'kelas_akademik'=> '7',
                'nama_ortu'     => 'Agus Rahayu',
                'no_ortu'       => '081234560002',
                'status'        => 'aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'Rizky Pratama',
                'no_hp'         => '081234567803',
                'email'         => 'rizky@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'kelas_akademik'=> '8',
                'nama_ortu'     => 'Hendra Pratama',
                'no_ortu'       => '081234560003',
                'status'        => 'aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'Siti Aisyah',
                'no_hp'         => '081234567804',
                'email'         => 'siti@gmail.com',
                'jenis_kelamin' => 'perempuan',
                'kelas_akademik'=> '8',
                'nama_ortu'     => 'Wahyu Aisyah',
                'no_ortu'       => '081234560004',
                'status'        => 'aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'Fajar Nugroho',
                'no_hp'         => '081234567805',
                'email'         => 'fajar@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'kelas_akademik'=> '9',
                'nama_ortu'     => 'Bambang Nugroho',
                'no_ortu'       => '081234560005',
                'status'        => 'aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'Putri Handayani',
                'no_hp'         => '081234567806',
                'email'         => 'putri@gmail.com',
                'jenis_kelamin' => 'perempuan',
                'kelas_akademik'=> '9',
                'nama_ortu'     => 'Eko Handayani',
                'no_ortu'       => '081234560006',
                'status'        => 'nonaktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
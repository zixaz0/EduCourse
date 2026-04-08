<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peserta;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        Peserta::insert([
            [
                'nama'         => 'Ferdinan Hidayat',
                'no_hp'        => '085678901234',
                'email'        => 'ferdi@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'level'        => 'cukup',
                'nama_ortu'    => 'Hendra Maulana',
                'no_ortu'      => '081122334455',
                'status'       => 'aktif',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama'         => 'Deny Firmansyah',
                'no_hp'        => '086789012345',
                'email'        => 'deny@gmail.com',
                'jenis_kelamin' => 'perempuan',
                'level'        => 'baik',
                'nama_ortu'    => 'Sari Handayani',
                'no_ortu'      => '082233445566',
                'status'       => 'aktif',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama'         => 'Enza Mulyono',
                'no_hp'        => '087890123456',
                'email'        => 'enza@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'level'        => 'mahir',
                'nama_ortu'    => 'Bambang Nugroho',
                'no_ortu'      => '083344556677',
                'status'       => 'aktif',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
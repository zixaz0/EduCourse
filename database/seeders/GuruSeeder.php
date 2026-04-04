<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        Guru::insert([
            [
                'nama'         => 'Ahmad Fauzi',
                'no_hp'        => '081234567890',
                'email'        => 'ahmad.fauzi@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama'         => 'Dewi Lestari',
                'no_hp'        => '082345678901',
                'email'        => 'dewi.lestari@gmail.com',
                'jenis_kelamin' => 'perempuan',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama'         => 'Riko Prasetyo',
                'no_hp'        => '083456789012',
                'email'        => 'riko.prasetyo@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
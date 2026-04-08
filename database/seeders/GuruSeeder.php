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
                'nama'         => 'Irsyad Fauzi',
                'no_hp'        => '081234567890',
                'email'        => 'irsyad@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama'         => 'Satria Maulana',
                'no_hp'        => '082345678901',
                'email'        => 'satria@gmail.com',
                'jenis_kelamin' => 'perempuan',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama'         => 'Alif Abdullah',
                'no_hp'        => '083456789012',
                'email'        => 'alif@gmail.com',
                'jenis_kelamin' => 'laki-laki',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PesertaKelas;

class PesertaKelasSeeder extends Seeder
{
    public function run(): void
    {
        PesertaKelas::insert([
            [
                'peserta_id' => 1,
                'kelas_id'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'peserta_id' => 2,
                'kelas_id'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'peserta_id' => 3,
                'kelas_id'   => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
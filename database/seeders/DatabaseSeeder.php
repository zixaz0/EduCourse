<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GuruSeeder::class,
            KelasSeeder::class,
            PesertaSeeder::class,
            PesertaKelasSeeder::class,
            TagihanSeeder::class,
            PembayaranSeeder::class,
            LogSeeder::class,
        ]);
    }
}
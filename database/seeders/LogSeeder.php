<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Log;

class LogSeeder extends Seeder
{
    public function run(): void
    {
        Log::insert([
            [
                'user_id'    => 1,
                'aktivitas'  => 'Login ke sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'    => 3,
                'aktivitas'  => 'Membuat transaksi TRX-20260401-001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'    => 2,
                'aktivitas'  => 'Menambah peserta baru: Fajar Nugroho',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
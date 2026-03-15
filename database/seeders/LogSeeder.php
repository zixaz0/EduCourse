<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('logs')->insert([
            [
                'user_id'    => 1,
                'aktivitas'  => 'Login ke sistem',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id'    => 1,
                'aktivitas'  => 'Menambahkan peserta baru: Budi Santoso',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id'    => 1,
                'aktivitas'  => 'Menambahkan peserta baru: Dewi Rahayu',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'user_id'    => 1,
                'aktivitas'  => 'Membuat tagihan bulan 01-2026 untuk semua peserta aktif',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id'    => 2,
                'aktivitas'  => 'Login ke sistem',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id'    => 2,
                'aktivitas'  => 'Memproses pembayaran tagihan ID #1 (Budi Santoso - Jan 2026)',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id'    => 2,
                'aktivitas'  => 'Memproses pembayaran tagihan ID #4 (Dewi Rahayu - Jan 2026)',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id'    => 1,
                'aktivitas'  => 'Mengubah status peserta: Putri Handayani menjadi nonaktif',
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'user_id'    => 1,
                'aktivitas'  => 'Membuat tagihan bulan 02-2026 untuk semua peserta aktif',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'user_id'    => 2,
                'aktivitas'  => 'Logout dari sistem',
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ],
        ]);
    }
}
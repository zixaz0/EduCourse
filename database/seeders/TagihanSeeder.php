<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagihanSeeder extends Seeder
{
    public function run(): void
    {
        // Total tagihan = jumlah harga kelas yang diikuti
        // Budi: Matematika Dasar (150k) + Bahasa Inggris (175k) = 325.000
        // Dewi: Bahasa Inggris (175k) + Bahasa Indonesia (150k) = 325.000
        // Rizky: IPA Terpadu (200k) + Matematika Lanjut (225k) = 425.000
        // Siti: Matematika Dasar (150k) + IPA Terpadu (200k) = 350.000
        // Fajar: Matematika Lanjut (225k) + Bahasa Inggris (175k) = 400.000
        // Putri: Bahasa Indonesia (150k) = 150.000

        DB::table('tagihan')->insert([
            // Budi - Januari & Februari 2026
            ['peserta_id' => 1, 'bulan_tahun' => '01-2026', 'total_tagihan' => 325000.00, 'status' => 'lunas',        'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 1, 'bulan_tahun' => '02-2026', 'total_tagihan' => 325000.00, 'status' => 'lunas',        'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 1, 'bulan_tahun' => '03-2026', 'total_tagihan' => 325000.00, 'status' => 'belum_lunas',  'created_at' => now(), 'updated_at' => now()],

            // Dewi - Januari & Februari 2026
            ['peserta_id' => 2, 'bulan_tahun' => '01-2026', 'total_tagihan' => 325000.00, 'status' => 'lunas',        'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 2, 'bulan_tahun' => '02-2026', 'total_tagihan' => 325000.00, 'status' => 'belum_lunas',  'created_at' => now(), 'updated_at' => now()],

            // Rizky
            ['peserta_id' => 3, 'bulan_tahun' => '01-2026', 'total_tagihan' => 425000.00, 'status' => 'lunas',        'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 3, 'bulan_tahun' => '02-2026', 'total_tagihan' => 425000.00, 'status' => 'lunas',        'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 3, 'bulan_tahun' => '03-2026', 'total_tagihan' => 425000.00, 'status' => 'belum_lunas',  'created_at' => now(), 'updated_at' => now()],

            // Siti
            ['peserta_id' => 4, 'bulan_tahun' => '01-2026', 'total_tagihan' => 350000.00, 'status' => 'lunas',        'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 4, 'bulan_tahun' => '02-2026', 'total_tagihan' => 350000.00, 'status' => 'belum_lunas',  'created_at' => now(), 'updated_at' => now()],

            // Fajar
            ['peserta_id' => 5, 'bulan_tahun' => '01-2026', 'total_tagihan' => 400000.00, 'status' => 'lunas',        'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 5, 'bulan_tahun' => '02-2026', 'total_tagihan' => 400000.00, 'status' => 'belum_lunas',  'created_at' => now(), 'updated_at' => now()],

            // Putri (nonaktif)
            ['peserta_id' => 6, 'bulan_tahun' => '01-2026', 'total_tagihan' => 150000.00, 'status' => 'lunas',        'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
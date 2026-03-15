<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesertaKelasSeeder extends Seeder
{
    public function run(): void
    {
        // Setiap peserta didaftarkan ke beberapa kelas
        DB::table('peserta_kelas')->insert([
            // Budi (id:1) → Matematika Dasar (id:1), Bahasa Inggris (id:2)
            ['peserta_id' => 1, 'kelas_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 1, 'kelas_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            // Dewi (id:2) → Bahasa Inggris (id:2), Bahasa Indonesia (id:4)
            ['peserta_id' => 2, 'kelas_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 2, 'kelas_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            // Rizky (id:3) → IPA Terpadu (id:3), Matematika Lanjut (id:5)
            ['peserta_id' => 3, 'kelas_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 3, 'kelas_id' => 5, 'created_at' => now(), 'updated_at' => now()],

            // Siti (id:4) → Matematika Dasar (id:1), IPA Terpadu (id:3)
            ['peserta_id' => 4, 'kelas_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 4, 'kelas_id' => 3, 'created_at' => now(), 'updated_at' => now()],

            // Fajar (id:5) → Matematika Lanjut (id:5), Bahasa Inggris (id:2)
            ['peserta_id' => 5, 'kelas_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['peserta_id' => 5, 'kelas_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            // Putri (id:6) → Bahasa Indonesia (id:4)
            ['peserta_id' => 6, 'kelas_id' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
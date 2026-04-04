<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tagihan;

class TagihanSeeder extends Seeder
{
    public function run(): void
    {
        Tagihan::insert([
            [
                'peserta_id'     => 1,
                'total_tagihan'  => 250000,
                'bulan_tahun'    => '2026-04',
                'status'         => 'belum_bayar',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'peserta_id'     => 2,
                'total_tagihan'  => 250000,
                'bulan_tahun'    => '2026-04',
                'status'         => 'lunas',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'peserta_id'     => 3,
                'total_tagihan'  => 300000,
                'bulan_tahun'    => '2026-04',
                'status'         => 'belum_bayar',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
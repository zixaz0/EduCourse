<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        Transaksi::insert([
            [
                'tagihan_id'     => 2,
                'nomor_unik'     => 'TRX-20260401-001',
                'uang_bayar'     => 300000,
                'uang_kembalian' => 50000,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transaksi')->insert([
            [
                'tagihan_id'     => 1,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 325000.00,
                'uang_kembalian' => 0.00,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 2,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 350000.00,
                'uang_kembalian' => 25000.00,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 4,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 325000.00,
                'uang_kembalian' => 0.00,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 6,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 425000.00,
                'uang_kembalian' => 0.00,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 7,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 500000.00,
                'uang_kembalian' => 75000.00,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 9,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 350000.00,
                'uang_kembalian' => 0.00,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 11,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 400000.00,
                'uang_kembalian' => 0.00,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 13,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 200000.00,
                'uang_kembalian' => 50000.00,
                'user_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
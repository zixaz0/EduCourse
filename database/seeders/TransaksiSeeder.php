<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // Transaksi hanya dibuat untuk tagihan yang statusnya 'lunas'
        // tagihan_id sesuai urutan insert di TagihanSeeder:
        //  1 = Budi Jan  (lunas) 325.000
        //  2 = Budi Feb  (lunas) 325.000
        //  3 = Budi Mar  (belum_lunas) → skip
        //  4 = Dewi Jan  (lunas) 325.000
        //  5 = Dewi Feb  (belum_lunas) → skip
        //  6 = Rizky Jan (lunas) 425.000
        //  7 = Rizky Feb (lunas) 425.000
        //  8 = Rizky Mar (belum_lunas) → skip
        //  9 = Siti Jan  (lunas) 350.000
        // 10 = Siti Feb  (belum_lunas) → skip
        // 11 = Fajar Jan (lunas) 400.000
        // 12 = Fajar Feb (belum_lunas) → skip
        // 13 = Putri Jan (lunas) 150.000

        DB::table('transaksi')->insert([
            [
                'tagihan_id'     => 1,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 325000.00,
                'uang_kembalian' => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 2,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 350000.00,
                'uang_kembalian' => 25000.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 4,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 325000.00,
                'uang_kembalian' => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 6,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 425000.00,
                'uang_kembalian' => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 7,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 500000.00,
                'uang_kembalian' => 75000.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 9,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 350000.00,
                'uang_kembalian' => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 11,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 400000.00,
                'uang_kembalian' => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tagihan_id'     => 13,
                'nomor_unik'     => 'TRX-' . strtoupper(uniqid()),
                'uang_bayar'     => 200000.00,
                'uang_kembalian' => 50000.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
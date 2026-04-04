<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Transaksi;
use App\Models\User;

class OwnerLaporanController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi dengan relasi yang dibutuhkan
        $rawTransaksi = Transaksi::with([
                'tagihan.peserta.kelas',
                'user',
            ])
            ->latest()
            ->get();

        // Map ke flat object sesuai yang dipakai di blade
        // Blade pakai: $t->peserta, $t->nomor_unik, $t->kasir, $t->kelas,
        //              $t->bulan_tahun, $t->total_tagihan, $t->uang_bayar,
        //              $t->uang_kembali, $t->created_at
        $transaksi = $rawTransaksi->map(function ($t) {
            $namaKelas   = optional($t->tagihan->peserta)->kelas->pluck('nama_kelas')->implode(', ') ?: '-';
            $bulanTahun  = $t->tagihan->bulan_tahun ?? '';

            // Konversi format "MM-YYYY" → "namabulan/YYYY" untuk filter di blade
            $parts      = explode('-', $bulanTahun);
            $bulanAngka = $parts[0] ?? '';
            $tahunAngka = $parts[1] ?? '';
            $bulanMap   = [
                '01' => 'januari', '02' => 'februari', '03' => 'maret',
                '04' => 'april',   '05' => 'mei',       '06' => 'juni',
                '07' => 'juli',    '08' => 'agustus',   '09' => 'september',
                '10' => 'oktober', '11' => 'november',  '12' => 'desember',
            ];
            $bulanNama   = $bulanMap[$bulanAngka] ?? $bulanAngka;
            $periodeLabel = ucfirst($bulanNama) . ' / ' . $tahunAngka;

            return (object) [
                'nomor_unik'    => $t->nomor_unik ?? '-',
                'peserta'       => $t->tagihan->peserta->nama ?? '-',
                'kelas'         => $namaKelas,
                'bulan_tahun'   => $periodeLabel,      // "Januari / 2026"
                'bulan_filter'  => $bulanNama,          // "januari" (untuk data-bulan)
                'tahun_filter'  => $tahunAngka,         // "2026"  (untuk data-tahun)
                'total_tagihan' => $t->tagihan->total_tagihan ?? 0,
                'uang_bayar'    => $t->uang_bayar ?? 0,
                'uang_kembali'  => $t->uang_kembalian ?? 0,
                'kasir'         => $t->user->username ?? $t->user->name ?? '-',
                'created_at'    => $t->created_at,
            ];
        });

        // Dropdown filter kasir
        $kasirList = User::where('role', 'kasir')
            ->orderBy('username')
            ->get();

        // Dropdown filter kelas
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('owner.laporan.index', compact('transaksi', 'kasirList', 'kelasList'));
    }
}
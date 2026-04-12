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
        $rawTransaksi = Transaksi::with([
                'tagihan.peserta.kelas', // fallback untuk tagihan lama tanpa snapshot
                'user',
            ])
            ->latest()
            ->get();

        $transaksi = $rawTransaksi->map(function ($t) {
            // ── Ambil kelas dari snapshot (frozen), fallback ke relasi peserta ──
            $kelasTampil = [];
            if (!empty($t->tagihan->kelas_snapshot)) {
                $kelasTampil = is_array($t->tagihan->kelas_snapshot)
                    ? $t->tagihan->kelas_snapshot
                    : json_decode($t->tagihan->kelas_snapshot, true) ?? [];
            } elseif ($t->tagihan && $t->tagihan->peserta) {
                $kelasTampil = $t->tagihan->peserta->kelas->pluck('nama_kelas')->toArray();
            }
            $namaKelas = implode(', ', $kelasTampil) ?: '-';
            // ────────────────────────────────────────────────────────────────────

            $bulanTahun  = $t->tagihan->bulan_tahun ?? '';
            $parts       = explode('-', $bulanTahun);
            $bulanAngka  = $parts[0] ?? '';
            $tahunAngka  = $parts[1] ?? '';
            $bulanMap    = [
                '01' => 'januari', '02' => 'februari', '03' => 'maret',
                '04' => 'april',   '05' => 'mei',       '06' => 'juni',
                '07' => 'juli',    '08' => 'agustus',   '09' => 'september',
                '10' => 'oktober', '11' => 'november',  '12' => 'desember',
            ];
            $bulanNama    = $bulanMap[$bulanAngka] ?? $bulanAngka;
            $periodeLabel = ucfirst($bulanNama) . ' / ' . $tahunAngka;

            return (object) [
                'nomor_unik'    => $t->nomor_unik ?? '-',
                'peserta'       => $t->tagihan->peserta->nama ?? '-',
                'kelas'         => $namaKelas,
                'bulan_tahun'   => $periodeLabel,
                'bulan_filter'  => $bulanNama,
                'tahun_filter'  => $tahunAngka,
                'total_tagihan' => $t->tagihan->total_tagihan ?? 0,
                'uang_bayar'    => $t->uang_bayar ?? 0,
                'uang_kembali'  => $t->uang_kembalian ?? 0,
                'kasir'         => $t->user->username ?? $t->user->name ?? '-',
                'created_at'    => $t->created_at,
            ];
        });

        $kasirList = User::where('role', 'kasir')->orderBy('username')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('owner.laporan.index', compact('transaksi', 'kasirList', 'kelasList'));
    }
}
<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Log;
use App\Models\Peserta;
use App\Models\Transaksi;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $totalKelas   = Kelas::count();
        $totalPeserta = Peserta::count();

        $bulanIni = now()->format('Y-m');

        $totalTransaksi = Transaksi::whereHas('tagihan', function ($q) use ($bulanIni) {
            $q->where('bulan_tahun', $bulanIni);
        })->count();

        $pemasukanBulanIni = Transaksi::whereHas('tagihan', function ($q) use ($bulanIni) {
            $q->where('bulan_tahun', $bulanIni);
        })->sum('uang_bayar');

        $kelasList  = Kelas::withCount('peserta')
            ->orderByDesc('peserta_count')
            ->limit(5)
            ->get();

        $maxPeserta = $kelasList->max('peserta_count') ?: 1;

        $kelasTerpopuler = $kelasList->map(function ($k) use ($maxPeserta) {
            return (object) [
                'nama_kelas'     => $k->nama_kelas,
                'jumlah_peserta' => $k->peserta_count,
                'persentase'     => round(($k->peserta_count / $maxPeserta) * 100),
            ];
        });
        $recentLog = Log::with('user')
            ->latest()
            ->limit(6)
            ->get();

        $recentTransaksi = Transaksi::with([
                'tagihan.peserta.kelas',
                'user',
            ])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($t) {
                $kelasTampil = [];
                if (!empty($t->tagihan->kelas_snapshot)) {
                    $kelasTampil = is_array($t->tagihan->kelas_snapshot)
                        ? $t->tagihan->kelas_snapshot
                        : json_decode($t->tagihan->kelas_snapshot, true) ?? [];
                } elseif ($t->tagihan && $t->tagihan->peserta) {
                    $kelasTampil = $t->tagihan->peserta->kelas->pluck('nama_kelas')->toArray();
                }

                return (object) [
                    'peserta' => $t->tagihan->peserta->nama ?? '-',
                    'kursus'  => implode(', ', $kelasTampil) ?: '-',
                    'jumlah'  => $t->uang_bayar ?? 0,
                    'kasir'   => $t->user->username ?? $t->user->name ?? '-',
                    'waktu'   => $t->created_at,
                ];
            });

        return view('owner.dashboard', [
            'stats' => [
                'totalKelas'        => $totalKelas,
                'totalPeserta'      => $totalPeserta,
                'totalTransaksi'    => $totalTransaksi,
                'pemasukanBulanIni' => $pemasukanBulanIni,
            ],
            'kelasTerpopuler' => $kelasTerpopuler,
            'recentLog'       => $recentLog,
            'recentTransaksi' => $recentTransaksi,
        ]);
    }
}
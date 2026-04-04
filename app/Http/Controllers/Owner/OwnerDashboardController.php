<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Log;
use App\Models\Peserta;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ──────────────────────────────────────────────
        $totalKelas    = Kelas::count();
        $totalPeserta  = Peserta::count();
        $totalTransaksi = Transaksi::count();

        // Pemasukan bulan ini: jumlah uang_bayar transaksi di bulan & tahun sekarang
        $bulanIni = now()->format('m');
        $tahunIni = now()->format('Y');

        $pemasukanBulanIni = Transaksi::whereHas('tagihan', function ($q) use ($bulanIni, $tahunIni) {
            // format bulan_tahun: "MM-YYYY"
            $q->where('bulan_tahun', $bulanIni . '-' . $tahunIni);
        })->sum('uang_bayar');

        // ── Kelas Terpopuler ─────────────────────────────────────────
        // Ambil kelas berdasarkan jumlah peserta terbanyak
        $kelasList = Kelas::withCount('peserta')
            ->orderByDesc('peserta_count')
            ->limit(5)
            ->get();

        $maxPeserta = $kelasList->max('peserta_count') ?: 1; // hindari division by zero

        $kelasTerpopuler = $kelasList->map(function ($k) use ($maxPeserta) {
            return (object) [
                'nama_kelas'     => $k->nama_kelas,
                'jumlah_peserta' => $k->peserta_count,
                'persentase'     => round(($k->peserta_count / $maxPeserta) * 100),
            ];
        });

        // ── Log Aktivitas Terbaru ────────────────────────────────────
        $recentLog = Log::with('user')
            ->latest()
            ->limit(6)
            ->get();

        // ── Transaksi Terbaru ────────────────────────────────────────
        $recentTransaksi = Transaksi::with([
                'tagihan.peserta.kelas',
                'user',
            ])
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($t) {
                return (object) [
                    'peserta' => $t->tagihan->peserta->nama ?? '-',
                    'kursus'  => $t->tagihan->peserta->kelas->pluck('nama_kelas')->implode(', ') ?: '-',
                    'jumlah'  => $t->uang_bayar ?? 0,
                    'kasir'   => $t->user->username ?? $t->user->name ?? '-',
                    'waktu'   => $t->created_at,
                ];
            });

        return view('owner.dashboard', [
            'stats' => [
                'totalKelas'         => $totalKelas,
                'totalPeserta'       => $totalPeserta,
                'totalTransaksi'     => $totalTransaksi,
                'pemasukanBulanIni'  => $pemasukanBulanIni,
            ],
            'kelasTerpopuler' => $kelasTerpopuler,
            'recentLog'       => $recentLog,
            'recentTransaksi' => $recentTransaksi,
        ]);
    }
}
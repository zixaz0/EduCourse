<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Peserta;
use App\Models\Transaksi;
use App\Models\Tagihan;
use App\Models\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ===== STAT CARDS =====
        $totalPeserta       = Peserta::count();
        $totalKelas         = Kelas::count();
        $totalTransaksi     = Transaksi::count();

        // ===== ROW 3: Transaksi Terbaru =====
        $recentTransaksi = Transaksi::with([
                                'tagihan.peserta.kelas',
                                'tagihan.peserta',
                            ])
                            ->whereHas('tagihan.peserta')
                            ->latest()
                            ->take(5)
                            ->get();

        // ===== ROW 3: Kelas Terpopuler =====
        $maxPeserta = Kelas::withCount('peserta')->get()->max('peserta_count') ?: 1;

        $kelasTerpopuler = Kelas::withCount('peserta')
                            ->orderByDesc('peserta_count')
                            ->take(5)
                            ->get()
                            ->map(function ($kelas) use ($maxPeserta) {
                                $kelas->jumlah_peserta = $kelas->peserta_count;
                                $kelas->persentase     = round(($kelas->peserta_count / $maxPeserta) * 100);
                                return $kelas;
                            });

        // ===== ROW 4: Log Aktivitas Terbaru =====
        $recentLog = Log::with('user')
                        ->latest()
                        ->take(8)
                        ->get();

        // ===== ROW 4: Tagihan Belum Lunas Terbaru =====
        $recentTagihanBelumLunas = Tagihan::with('peserta')
                                    ->where('status', 'belum_lunas')
                                    ->latest()
                                    ->take(6)
                                    ->get();

        return view('admin.dashboard', compact(
            'totalPeserta',
            'totalKelas',
            'totalTransaksi',
            'recentTransaksi',
            'kelasTerpopuler',
            'recentLog',
            'recentTagihanBelumLunas',
        ));
    }
}
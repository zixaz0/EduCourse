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
        $totalPeserta       = Peserta::where('status', 'aktif')->count();
        $totalKelas         = Kelas::count();
        $totalTransaksi     = Transaksi::count();

        // ===== ROW 3: Kelas Terpopuler =====
        $maxPeserta = Kelas::withCount(['peserta' => fn($q) => $q->where('status', 'aktif')])->get()->max('peserta_count') ?: 1;

        $kelasTerpopuler = Kelas::withCount(['peserta' => fn($q) => $q->where('status', 'aktif')])
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
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->take(8)
                        ->get();

        // ===== ROW 4: Tagihan Belum Lunas Terbaru =====
        $recentTagihanBelumLunas = Tagihan::with('peserta')
                                    ->whereHas('peserta', fn($q) => $q->where('status', 'aktif'))
                                    ->where('status', 'belum_lunas')
                                    ->latest()
                                    ->take(6)
                                    ->get();

        return view('admin.dashboard', compact(
            'totalPeserta',
            'totalKelas',
            'totalTransaksi',
            'kelasTerpopuler',
            'recentLog',
            'recentTagihanBelumLunas',
        ));
    }
}
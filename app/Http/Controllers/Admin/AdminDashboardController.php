<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Kelas;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\Log;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->format('m-Y'); // format: 03-2026

        // ===== ROW 1 =====
        $totalUser   = User::count();
        $userAktif   = User::whereDate('updated_at', today())->count(); // user yang ada aktivitas hari ini
        $totalPeserta = Peserta::count();
        $pesertaAktif = Peserta::where('status', 'aktif')->count();

        // ===== ROW 2 =====
        $totalKelas         = Kelas::count();
        $tagihanBelumLunas  = Tagihan::where('status', 'belum_lunas')->count();
        $pemasukanBulanIni  = Transaksi::join('tagihan', 'transaksi.tagihan_id', '=', 'tagihan.id')
                                ->where('tagihan.bulan_tahun', $bulanIni)
                                ->where('tagihan.status', 'lunas')
                                ->sum('transaksi.uang_bayar');
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
            'totalUser',
            'userAktif',
            'totalPeserta',
            'pesertaAktif',
            'totalKelas',
            'tagihanBelumLunas',
            'pemasukanBulanIni',
            'totalTransaksi',
            'recentTransaksi',
            'kelasTerpopuler',
            'recentLog',
            'recentTagihanBelumLunas',
        ));
    }
}
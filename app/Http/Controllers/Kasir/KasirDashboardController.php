<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Peserta;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->format('m-Y');

        $totalKelas          = Kelas::count();
        $pesertaAktif        = Peserta::where('status', 'aktif')->count();
        $tagihanBelumDibayar = Tagihan::where('status', 'belum_lunas')->count();
        $recentTagihan = Tagihan::with('peserta')
                            ->where('status', 'belum_lunas')
                            ->latest()
                            ->take(5)
                            ->get();

        $recentTransaksi = Transaksi::with('tagihan.peserta')
                            ->where('user_id', Auth::id())
                            ->latest()
                            ->take(5)
                            ->get();

        $pemasukanBulanIni = Transaksi::join('tagihan', 'transaksi.tagihan_id', '=', 'tagihan.id')
                                ->where('transaksi.user_id', Auth::id())
                                ->where('tagihan.bulan_tahun', $bulanIni)
                                ->where('tagihan.status', 'lunas')
                                ->sum('transaksi.uang_bayar');

        return view('kasir.dashboard', compact(
            'totalKelas',
            'pesertaAktif',
            'tagihanBelumDibayar',
            'recentTagihan',
            'recentTransaksi',
            'pemasukanBulanIni',
        ));
    }
}
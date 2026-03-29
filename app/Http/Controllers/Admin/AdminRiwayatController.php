<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRiwayatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->get('per_page'), [5, 10, 25, 50]) ? (int) $request->get('per_page') : 10;

        $riwayat = Transaksi::with([
                                'peserta',           // ambil nama peserta langsung dari transaksi.peserta_id
                                'tagihan.peserta.kelas', // untuk kolom kursus
                                'user',              // ambil username kasir dari transaksi.user_id
                            ])
                            ->latest()
                            ->paginate($perPage)
                            ->withQueryString();

        // Dropdown filter kasir
        $kasirList = User::where('role', 'kasir')->orderBy('username')->get();

        // Dropdown filter kursus
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // Stat cards
        $totalTransaksi   = Transaksi::count();
        $totalPemasukan   = Transaksi::join('tagihan', 'transaksi.tagihan_id', '=', 'tagihan.id')
                                     ->sum('tagihan.total_tagihan');
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();

        return view('admin.riwayat.index', compact(
            'riwayat',
            'kasirList',
            'kelasList',
            'perPage',
            'totalTransaksi',
            'totalPemasukan',
            'transaksiHariIni',
        ));
    }
}
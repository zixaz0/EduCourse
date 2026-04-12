<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kelas;

class KasirRiwayatController extends Controller
{
    public function index()
    {
        $riwayat   = Transaksi::with(['tagihan.peserta.kelas', 'user'])
                        ->latest()
                        ->get();

        $kelasList = Kelas::all();

        return view('kasir.riwayat.index', compact('riwayat', 'kelasList'));
    }
}
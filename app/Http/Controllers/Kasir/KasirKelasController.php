<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kelas;

class KasirKelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['guru', 'peserta'])
                      ->withCount(['peserta' => fn($q) => $q->where('status', 'aktif')])
                      ->latest()
                      ->get();

        $kelas->each(function ($k) {
            $k->jumlah_peserta = $k->peserta_count;
        });

        return view('kasir.kelas.index', compact('kelas'));
    }
}
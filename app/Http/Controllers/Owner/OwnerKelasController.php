<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kelas;

class OwnerKelasController extends Controller
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

        return view('owner.kelas.index', compact('kelas'));
    }
}
<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kelas;

class KasirKelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['guru', 'peserta'])
                      ->withCount(['peserta' => fn($q) => $q->where('peserta.status', 'aktif')])
                      ->latest()
                      ->get();

        $kelas->each(function ($k) {
            $k->jumlah_peserta = $k->peserta_count;
        });

        // Siapkan data JSON untuk modal detail (hindari arrow function di Blade @json)
        $kelasJson = $kelas->map(function ($k) {
            return [
                'id'             => $k->id,
                'nama_kelas'     => $k->nama_kelas,
                'guru'           => $k->guru->nama ?? '-',
                'harga_kelas'    => $k->harga_kelas,
                'hari_kelas'     => $k->hari_kelas,
                'jam_mulai'      => $k->jam_mulai,
                'jam_selesai'    => $k->jam_selesai,
                'deskripsi'      => $k->deskripsi ?? '',
                'jumlah_peserta' => $k->jumlah_peserta ?? 0,
                'peserta'        => $k->peserta->map(function ($p) {
                    return [
                        'nama'   => $p->nama,
                        'no_hp'  => $p->no_hp,
                        'level'  => $p->level,
                        'status' => strtolower($p->status ?? 'aktif'),
                    ];
                })->values(),
                'created_at'     => $k->created_at ? $k->created_at->format('d M Y') : '-',
            ];
        });

        return view('kasir.kelas.index', compact('kelas', 'kelasJson'));
    }
}
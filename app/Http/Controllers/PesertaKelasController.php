<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Peserta;
use App\Models\PesertaKelas;
use Illuminate\Http\Request;

class PesertaKelasController extends Controller
{
    /**
     * Tampilkan semua relasi peserta-kelas.
     */
    public function index()
    {
        $data = PesertaKelas::with(['peserta', 'kelas'])->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Tambah peserta ke kelas tertentu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'kelas_id'   => 'required|exists:kelas,id',
        ]);

        $peserta = Peserta::findOrFail($validated['peserta_id']);

        // Cek apakah sudah terdaftar
        if ($peserta->kelas()->where('kelas_id', $validated['kelas_id'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah terdaftar di kelas ini.',
            ], 409);
        }

        $peserta->kelas()->attach($validated['kelas_id']);

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil didaftarkan ke kelas.',
        ], 201);
    }

    /**
     * Hapus peserta dari kelas tertentu.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'kelas_id'   => 'required|exists:kelas,id',
        ]);

        $peserta = Peserta::findOrFail($validated['peserta_id']);
        $peserta->kelas()->detach($validated['kelas_id']);

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil dikeluarkan dari kelas.',
        ]);
    }

    /**
     * Tampilkan semua kelas yang diikuti satu peserta.
     */
    public function kelasByPeserta($pesertaId)
    {
        $peserta = Peserta::with('kelas')->findOrFail($pesertaId);

        return response()->json([
            'success' => true,
            'data'    => $peserta->kelas,
        ]);
    }

    /**
     * Tampilkan semua peserta di satu kelas.
     */
    public function pesertaByKelas($kelasId)
    {
        $kelas = Kelas::with('peserta')->findOrFail($kelasId);

        return response()->json([
            'success' => true,
            'data'    => $kelas->peserta,
        ]);
    }
}
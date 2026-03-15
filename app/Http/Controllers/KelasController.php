<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    /**
     * Tampilkan semua kelas.
     */
    public function index()
    {
        $kelas = Kelas::with(['user', 'peserta'])->get();

        return response()->json([
            'success' => true,
            'data'    => $kelas,
        ]);
    }

    /**
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas'  => 'required|string|max:255',
            'harga_kelas' => 'required|numeric|min:0',
            'hari_kelas'  => 'required|string|max:100',
        ]);

        $kelas = Kelas::create([
            'user_id'     => Auth::id(),
            'nama_kelas'  => $validated['nama_kelas'],
            'harga_kelas' => $validated['harga_kelas'],
            'hari_kelas'  => $validated['hari_kelas'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dibuat.',
            'data'    => $kelas->load('user'),
        ], 201);
    }

    /**
     * Tampilkan detail satu kelas.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['user', 'peserta'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $kelas,
        ]);
    }

    /**
     * Update kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'nama_kelas'  => 'sometimes|required|string|max:255',
            'harga_kelas' => 'sometimes|required|numeric|min:0',
            'hari_kelas'  => 'sometimes|required|string|max:100',
        ]);

        $kelas->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diperbarui.',
            'data'    => $kelas->fresh('user'),
        ]);
    }

    /**
     * Hapus kelas.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus.',
        ]);
    }

    /**
     * Tampilkan semua peserta dalam sebuah kelas.
     */
    public function peserta($id)
    {
        $kelas = Kelas::with('peserta')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $kelas->peserta,
        ]);
    }
}
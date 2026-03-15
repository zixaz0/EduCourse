<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    /**
     * Tampilkan semua peserta.
     */
    public function index(Request $request)
    {
        $query = Peserta::with(['kelas', 'tagihan']);

        // Filter opsional berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $peserta = $query->get();

        return response()->json([
            'success' => true,
            'data'    => $peserta,
        ]);
    }

    /**
     * Simpan peserta baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'email'        => 'required|email|unique:peserta,email',
            'kelas'        => 'nullable|string|max:100',
            'no_hp'        => 'required|string|max:20',
            'nama_orangtua'=> 'nullable|string|max:255',
            'no_orangtua'  => 'nullable|string|max:20',
            'status'       => 'required|string|in:aktif,nonaktif',
        ]);

        $peserta = Peserta::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil ditambahkan.',
            'data'    => $peserta,
        ], 201);
    }

    /**
     * Tampilkan detail satu peserta.
     */
    public function show($id)
    {
        $peserta = Peserta::with(['kelas', 'tagihan', 'transaksi'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $peserta,
        ]);
    }

    /**
     * Update data peserta.
     */
    public function update(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $validated = $request->validate([
            'nama'         => 'sometimes|required|string|max:255',
            'email'        => 'sometimes|required|email|unique:peserta,email,' . $id,
            'kelas'        => 'nullable|string|max:100',
            'no_hp'        => 'sometimes|required|string|max:20',
            'nama_orangtua'=> 'nullable|string|max:255',
            'no_orangtua'  => 'nullable|string|max:20',
            'status'       => 'sometimes|required|string|in:aktif,nonaktif',
        ]);

        $peserta->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data peserta berhasil diperbarui.',
            'data'    => $peserta->fresh(),
        ]);
    }

    /**
     * Hapus peserta.
     */
    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil dihapus.',
        ]);
    }

    /**
     * Daftarkan peserta ke kelas (Many-to-Many).
     */
    public function daftarKelas(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $request->validate([
            'kelas_ids'   => 'required|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        // sync() akan replace semua, attach() untuk tambah saja
        $peserta->kelas()->sync($request->kelas_ids);

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil didaftarkan ke kelas.',
            'data'    => $peserta->load('kelas'),
        ]);
    }
}
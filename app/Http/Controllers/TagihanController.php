<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Peserta;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Tampilkan semua tagihan.
     */
    public function index(Request $request)
    {
        $query = Tagihan::with(['peserta', 'transaksi']);

        // Filter opsional berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter opsional berdasarkan peserta
        if ($request->has('peserta_id')) {
            $query->where('peserta_id', $request->peserta_id);
        }

        // Filter opsional berdasarkan bulan/tahun, contoh: "2025-01"
        if ($request->has('bulan_tahun')) {
            $query->where('bulan_tahun', $request->bulan_tahun);
        }

        $tagihan = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $tagihan,
        ]);
    }

    /**
     * Buat tagihan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_id'    => 'required|exists:peserta,id',
            'total_tagihan' => 'required|numeric|min:0',
            'bulan_tahun'   => 'required|string|max:20',
            'status'        => 'required|string|in:belum_bayar,lunas,cicilan',
        ]);

        // Cek apakah tagihan bulan yang sama sudah ada
        $exists = Tagihan::where('peserta_id', $validated['peserta_id'])
            ->where('bulan_tahun', $validated['bulan_tahun'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan untuk peserta ini di bulan tersebut sudah ada.',
            ], 409);
        }

        $tagihan = Tagihan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil dibuat.',
            'data'    => $tagihan->load('peserta'),
        ], 201);
    }

    /**
     * Tampilkan detail satu tagihan.
     */
    public function show($id)
    {
        $tagihan = Tagihan::with(['peserta', 'transaksi'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $tagihan,
        ]);
    }

    /**
     * Update tagihan.
     */
    public function update(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);

        $validated = $request->validate([
            'peserta_id'    => 'sometimes|required|exists:peserta,id',
            'total_tagihan' => 'sometimes|required|numeric|min:0',
            'bulan_tahun'   => 'sometimes|required|string|max:20',
            'status'        => 'sometimes|required|string|in:belum_bayar,lunas,cicilan',
        ]);

        $tagihan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil diperbarui.',
            'data'    => $tagihan->fresh('peserta'),
        ]);
    }

    /**
     * Hapus tagihan.
     */
    public function destroy($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil dihapus.',
        ]);
    }

    /**
     * Generate tagihan bulanan untuk semua peserta aktif.
     */
    public function generateBulanan(Request $request)
    {
        $request->validate([
            'bulan_tahun' => 'required|string|max:20',
        ]);

        $pesertaAktif = Peserta::where('status', 'aktif')->with('kelas')->get();
        $created = [];

        foreach ($pesertaAktif as $peserta) {
            // Hitung total tagihan dari semua kelas yang diikuti
            $totalTagihan = $peserta->kelas->sum('harga_kelas');

            // Skip jika tidak ada kelas
            if ($totalTagihan <= 0) continue;

            // Skip jika tagihan bulan ini sudah ada
            $exists = Tagihan::where('peserta_id', $peserta->id)
                ->where('bulan_tahun', $request->bulan_tahun)
                ->exists();

            if (!$exists) {
                $tagihan = Tagihan::create([
                    'peserta_id'    => $peserta->id,
                    'total_tagihan' => $totalTagihan,
                    'bulan_tahun'   => $request->bulan_tahun,
                    'status'        => 'belum_bayar',
                ]);
                $created[] = $tagihan;
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($created) . ' tagihan berhasil digenerate.',
            'data'    => $created,
        ]);
    }
}
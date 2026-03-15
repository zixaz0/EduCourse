<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Tampilkan semua transaksi.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['tagihan', 'peserta', 'user']);

        // Filter opsional berdasarkan peserta
        if ($request->has('peserta_id')) {
            $query->where('peserta_id', $request->peserta_id);
        }

        // Filter opsional berdasarkan tagihan
        if ($request->has('tagihan_id')) {
            $query->where('tagihan_id', $request->tagihan_id);
        }

        $transaksi = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $transaksi,
        ]);
    }

    /**
     * Proses transaksi pembayaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tagihan_id'  => 'required|exists:tagihan,id',
            'peserta_id'  => 'required|exists:peserta,id',
            'uang_bayar'  => 'required|numeric|min:0',
        ]);

        $tagihan = Tagihan::findOrFail($validated['tagihan_id']);

        // Validasi uang bayar tidak boleh kurang dari total tagihan
        if ($validated['uang_bayar'] < $tagihan->total_tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Uang bayar tidak boleh kurang dari total tagihan.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Generate nomor unik
            $nomorUnik = 'TRX-' . strtoupper(uniqid());

            $uangKembali = $validated['uang_bayar'] - $tagihan->total_tagihan;

            $transaksi = Transaksi::create([
                'tagihan_id'  => $validated['tagihan_id'],
                'peserta_id'  => $validated['peserta_id'],
                'nomor_unik'  => $nomorUnik,
                'uang_bayar'  => $validated['uang_bayar'],
                'uang_kembali'=> $uangKembali,
                'user_id'     => Auth::id(),   // Note: kolom di DB harus 'user_id', bukan 'id_user'
            ]);

            // Update status tagihan menjadi lunas
            $tagihan->update(['status' => 'lunas']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses.',
                'data'    => $transaksi->load(['tagihan', 'peserta', 'user']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu transaksi.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['tagihan', 'peserta', 'user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $transaksi,
        ]);
    }

    /**
     * Update transaksi (misal koreksi data).
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validated = $request->validate([
            'uang_bayar' => 'sometimes|required|numeric|min:0',
        ]);

        // Hitung ulang uang kembali jika uang_bayar diubah
        if (isset($validated['uang_bayar'])) {
            $tagihan = Tagihan::findOrFail($transaksi->tagihan_id);
            $validated['uang_kembali'] = $validated['uang_bayar'] - $tagihan->total_tagihan;
        }

        $transaksi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diperbarui.',
            'data'    => $transaksi->fresh(['tagihan', 'peserta', 'user']),
        ]);
    }

    /**
     * Hapus transaksi.
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        DB::beginTransaction();
        try {
            // Kembalikan status tagihan ke belum_bayar
            $tagihan = Tagihan::find($transaksi->tagihan_id);
            if ($tagihan) {
                $tagihan->update(['status' => 'belum_bayar']);
            }

            $transaksi->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus dan status tagihan dikembalikan.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
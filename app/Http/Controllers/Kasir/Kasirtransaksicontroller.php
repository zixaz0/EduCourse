<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\Kelas;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KasirTransaksiController extends Controller
{
    // ==========================================
    // INDEX — Daftar semua tagihan
    // ==========================================
    public function index()
    {
        $tagihan   = Tagihan::with('peserta.kelas')->latest()->get();
        $kelasList = Kelas::all();

        return view('kasir.transaksi.index', compact('tagihan', 'kelasList'));
    }

    // ==========================================
    // BAYAR — Form pembayaran tagihan
    // ==========================================
    public function bayar($id)
    {
        $tagihan = Tagihan::with('peserta.kelas')->findOrFail($id);

        // Kalau sudah lunas, redirect balik
        if (strtolower($tagihan->status) === 'lunas') {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Tagihan ini sudah lunas.');
        }

        return view('kasir.transaksi.bayar', compact('tagihan'));
    }

    // ==========================================
    // PROSES — Simpan transaksi pembayaran
    // ==========================================
    public function proses(Request $request, $id)
    {
        $request->validate([
            'nomor_unik' => 'required|string|unique:transaksi,nomor_unik',
            'uang_bayar' => 'required|numeric|min:1',
        ]);

        $tagihan = Tagihan::findOrFail($id);

        if ((float) $request->uang_bayar < (float) $tagihan->total_tagihan) {
            return back()->with('error', 'Uang bayar kurang dari total tagihan.');
        }

        $kembalian = (float) $request->uang_bayar - (float) $tagihan->total_tagihan;

        // Simpan transaksi
        Transaksi::create([
            'tagihan_id'     => $tagihan->id,
            'nomor_unik'     => $request->nomor_unik,
            'uang_bayar'     => $request->uang_bayar,
            'uang_kembalian' => $kembalian,
            'user_id'        => Auth::id(),
        ]);

        // Update status tagihan jadi lunas
        $tagihan->update(['status' => 'lunas']);

        // Log aktivitas
        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Memproses pembayaran tagihan ' . $tagihan->bulan_tahun . ' untuk peserta: ' . ($tagihan->peserta->nama ?? '-'),
        ]);

        return redirect()->route('kasir.transaksi.index')
            ->with('success', 'Pembayaran berhasil! Kembalian: Rp ' . number_format($kembalian, 0, ',', '.'));
    }

    // ==========================================
    // DESTROY — Hapus tagihan (hanya belum lunas)
    // ==========================================
    public function destroy($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if (strtolower($tagihan->status) === 'lunas') {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Tagihan yang sudah lunas tidak bisa dihapus.');
        }

        $nama = $tagihan->peserta->nama ?? '-';
        $tagihan->delete();

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menghapus tagihan peserta: ' . $nama,
        ]);

        return redirect()->route('kasir.transaksi.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
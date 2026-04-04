<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Log;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPesertaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->get('per_page'), [5, 10, 25, 50]) ? (int) $request->get('per_page') : 10;

        $peserta = Peserta::with('kelas')
                          ->where('status', 'aktif')
                          ->latest()
                          ->paginate($perPage)
                          ->withQueryString();

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $levelList = Peserta::where('status', 'aktif')
                            ->whereNotNull('level')
                            ->distinct()
                            ->orderBy('level')
                            ->pluck('level');

        return view('admin.peserta.index', compact('peserta', 'kelasList', 'levelList', 'perPage'));
    }

    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);

        if ($peserta->status === 'nonaktif') {
            return redirect()->route('admin.peserta.index')
                             ->with('error', 'Peserta sudah dalam status non-aktif.');
        }

        $peserta->update(['status' => 'nonaktif']);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Hapus peserta: ' . $peserta->nama,
        ]);

        return redirect()->route('admin.peserta.index')
                         ->with('success', 'Peserta ' . $peserta->nama . ' berhasil dihapus.');
    }
}
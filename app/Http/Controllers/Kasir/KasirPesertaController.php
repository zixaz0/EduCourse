<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Kelas;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirPesertaController extends Controller
{
    // ==========================================
    // INDEX
    // ==========================================
    public function index()
    {
        $peserta   = Peserta::with('kelas')->get();
        $kelasList = Kelas::all();

        return view('kasir.peserta.index', compact('peserta', 'kelasList'));
    }

    // ==========================================
    // ADD — Form tambah peserta
    // ==========================================
    public function add()
    {
        $kelasList = Kelas::all();

        return view('kasir.peserta.add', compact('kelasList'));
    }

    // ==========================================
    // STORE — Simpan peserta baru
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'email'         => 'required|email|unique:peserta,email',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'level'         => 'required|in:cukup,baik,mahir',
            'nama_ortu'     => 'required|string|max:255',
            'no_ortu'       => 'required|string|max:20',
            'kelas'         => 'required|array|min:1',
            'kelas.*'       => 'exists:kelas,id',
        ]);

        $peserta = Peserta::create([
            'nama'          => $request->nama,
            'no_hp'         => $request->no_hp,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'level'=> $request->level,
            'nama_ortu'     => $request->nama_ortu,
            'no_ortu'       => $request->no_ortu,
            'status'        => 'aktif', // default selalu aktif saat tambah
        ]);

        $peserta->kelas()->sync($request->kelas);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Tambah peserta baru: ' . $peserta->nama,
        ]);

        return redirect()->route('kasir.peserta.index')
            ->with('success', "Peserta {$peserta->nama} berhasil ditambahkan.");
    }

    // ==========================================
    // EDIT — Form edit peserta
    // ==========================================
    public function edit($id)
    {
        $peserta   = Peserta::with('kelas')->findOrFail($id);
        $kelasList = Kelas::all();

        return view('kasir.peserta.edit', compact('peserta', 'kelasList'));
    }

    // ==========================================
    // UPDATE — Simpan perubahan peserta (status tidak ikut diubah)
    // ==========================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'email'         => 'required|email|unique:peserta,email,' . $id,
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'level'         => 'required|in:cukup,baik,mahir',
            'nama_ortu'     => 'required|string|max:255',
            'no_ortu'       => 'required|string|max:20',
            'kelas'         => 'required|array|min:1',
            'kelas.*'       => 'exists:kelas,id',
        ]);

        $peserta = Peserta::findOrFail($id);

        $peserta->update([
            'nama'          => $request->nama,
            'no_hp'         => $request->no_hp,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'level'=> $request->level,
            'nama_ortu'     => $request->nama_ortu,
            'no_ortu'       => $request->no_ortu,
            // status TIDAK diubah lewat form edit
        ]);

        $peserta->kelas()->sync($request->kelas);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Edit data peserta: ' . $peserta->nama,
        ]);

        return redirect()->route('kasir.peserta.index')
            ->with('success', "Data {$peserta->nama} berhasil diperbarui.");
    }

    // ==========================================
    // TOGGLE STATUS — Aktif / Nonaktif (dari tombol di index)
    // ==========================================
    public function toggle($id)
    {
        $peserta         = Peserta::findOrFail($id);
        $peserta->status = $peserta->status === 'aktif' ? 'nonaktif' : 'aktif';
        $peserta->save();

        $label = $peserta->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Mengubah status peserta: ' . $peserta->nama . ' menjadi ' . $peserta->status,
        ]);

        return redirect()->route('kasir.peserta.index')
            ->with('success', "Peserta {$peserta->nama} berhasil {$label}.");
    }
}
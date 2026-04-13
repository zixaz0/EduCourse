<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGuruController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->get('per_page'), [5, 10, 25, 50]) ? (int) $request->get('per_page') : 10;

        $guru = Guru::with('kelas')->latest()->paginate($perPage)->withQueryString();

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('admin.guru.index', compact('guru', 'perPage', 'kelasList'));
    }

    public function add()
    {
        return view('admin.guru.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'email'         => 'required|email|unique:guru,email',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        $guru = Guru::create($request->only('nama', 'no_hp', 'email', 'jenis_kelamin'));

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Tambah guru: ' . $guru->nama,
        ]);

        return redirect()->route('admin.guru.index')
                         ->with('success', 'Guru ' . $guru->nama . ' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama'          => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'email'         => 'required|email|unique:guru,email,' . $id,
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        $guru->update($request->only('nama', 'no_hp', 'email', 'jenis_kelamin'));

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Edit guru: ' . $guru->nama,
        ]);

        return redirect()->route('admin.guru.index')
                         ->with('success', 'Guru ' . $guru->nama . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = Guru::with('kelas')->findOrFail($id);

        if ($guru->kelas->isNotEmpty()) {
            $namaKelas = $guru->kelas->pluck('nama_kelas')->implode(', ');

            return redirect()->route('admin.guru.index')
                             ->with('error_hapus_guru', [
                                 'nama'       => $guru->nama,
                                 'kelas_list' => $guru->kelas->pluck('nama_kelas')->toArray(),
                             ]);
        }

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Hapus guru: ' . $guru->nama,
        ]);

        $guru->delete();

        return redirect()->route('admin.guru.index')
                         ->with('success', 'Guru ' . $guru->nama . ' berhasil dihapus.');
    }
}
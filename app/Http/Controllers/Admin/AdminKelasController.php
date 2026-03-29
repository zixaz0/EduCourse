<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminKelasController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->get('per_page'), [5, 10, 25, 50]) ? (int) $request->get('per_page') : 10;

        $kelas = Kelas::withCount(['peserta' => fn($q) => $q->where('status', 'aktif')])
                      ->latest()
                      ->paginate($perPage)
                      ->withQueryString();

        $kelas->getCollection()->transform(function ($k) {
            $k->jumlah_peserta = $k->peserta_count;
            return $k;
        });

        return view('admin.kelas.index', compact('kelas', 'perPage'));
    }

    public function add()
    {
        return view('admin.kelas.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas'  => 'required|string|max:255|unique:kelas,nama_kelas',
            'harga_kelas' => 'required|numeric|min:0',
            'hari_kelas'  => 'required|array|min:1',
            'hari_kelas.*'=> 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        ], [
            'nama_kelas.required'  => 'Nama kelas wajib diisi.',
            'nama_kelas.unique'    => 'Nama kelas sudah ada.',
            'harga_kelas.required' => 'Harga kelas wajib diisi.',
            'hari_kelas.required'  => 'Pilih minimal satu hari.',
            'hari_kelas.min'       => 'Pilih minimal satu hari.',
        ]);

        $kelas = Kelas::create([
            'nama_kelas'  => $validated['nama_kelas'],
            'harga_kelas' => $validated['harga_kelas'],
            'hari_kelas'  => implode(', ', $validated['hari_kelas']),
        ]);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Tambah kelas baru: ' . $kelas->nama_kelas,
        ]);

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Kelas ' . $kelas->nama_kelas . ' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kelas = Kelas::withCount(['peserta' => fn($q) => $q->where('status', 'aktif')])->findOrFail($id);
        $kelas->jumlah_peserta = $kelas->peserta_count;

        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'nama_kelas'  => 'required|string|max:255|unique:kelas,nama_kelas,' . $id,
            'harga_kelas' => 'required|numeric|min:0',
            'hari_kelas'  => 'required|array|min:1',
            'hari_kelas.*'=> 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        ], [
            'nama_kelas.required'  => 'Nama kelas wajib diisi.',
            'nama_kelas.unique'    => 'Nama kelas sudah dipakai.',
            'harga_kelas.required' => 'Harga kelas wajib diisi.',
            'hari_kelas.required'  => 'Pilih minimal satu hari.',
            'hari_kelas.min'       => 'Pilih minimal satu hari.',
        ]);

        $kelas->update([
            'nama_kelas'  => $validated['nama_kelas'],
            'harga_kelas' => $validated['harga_kelas'],
            'hari_kelas'  => implode(', ', $validated['hari_kelas']),
        ]);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Edit kelas: ' . $kelas->nama_kelas,
        ]);

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Kelas ' . $kelas->nama_kelas . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::withCount(['peserta' => fn($q) => $q->where('status', 'aktif')])->findOrFail($id);

        if ($kelas->peserta_count > 0) {
            return redirect()->route('admin.kelas.index')
                             ->with('error', 'Kelas tidak bisa dihapus karena masih ada ' . $kelas->peserta_count . ' peserta aktif.');
        }

        $namaKelas = $kelas->nama_kelas;
        $kelas->delete();

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Hapus kelas: ' . $namaKelas,
        ]);

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Kelas ' . $namaKelas . ' berhasil dihapus.');
    }
}
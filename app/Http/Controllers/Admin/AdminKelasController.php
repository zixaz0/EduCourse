<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminKelasController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->get('per_page'), [5, 10, 25, 50]) ? (int) $request->get('per_page') : 10;

        $kelas = Kelas::with(['guru', 'peserta' => fn($q) => $q->where('peserta.status', 'aktif')])
                      ->withCount(['peserta' => fn($q) => $q->where('peserta.status', 'aktif')])
                      ->latest()
                      ->paginate($perPage)
                      ->withQueryString();

        $kelas->getCollection()->transform(function ($k) {
            $k->jumlah_peserta = $k->peserta_count;
            return $k;
        });

        $kelasJson = $kelas->getCollection()->map(function ($k) {
            return [
                'id'             => $k->id,
                'nama_kelas'     => $k->nama_kelas,
                'guru'           => $k->guru->nama ?? '-',
                'harga_kelas'    => $k->harga_kelas,
                'hari_kelas'     => $k->hari_kelas,
                'jam_mulai'      => $k->jam_mulai,
                'jam_selesai'    => $k->jam_selesai,
                'deskripsi'      => $k->deskripsi ?? '',
                'jumlah_peserta' => $k->jumlah_peserta ?? 0,
                'peserta'        => $k->peserta->map(function ($p) {
                    return [
                        'nama'  => $p->nama,
                        'no_hp' => $p->no_hp,
                        'level' => $p->level,
                    ];
                })->values(),
                'edit_url'       => route('admin.kelas.edit', $k->id),
                'created_at'     => $k->created_at ? $k->created_at->format('d M Y') : '-',
            ];
        });

        return view('admin.kelas.index', compact('kelas', 'perPage', 'kelasJson'));
    }

    public function add()
    {
        $guruList = Guru::orderBy('nama')->get();
        return view('admin.kelas.add', compact('guruList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas'  => 'required|string|max:255|unique:kelas,nama_kelas',
            'harga_kelas' => 'required|numeric|min:0',
            'hari_kelas'  => 'required|array|min:1',
            'hari_kelas.*'=> 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|string',
            'jam_selesai' => 'required|string',
            'guru_id'     => 'required|exists:guru,id',
            'deskripsi'   => 'nullable|string',
        ], [
            'nama_kelas.required'  => 'Nama kelas wajib diisi.',
            'nama_kelas.unique'    => 'Nama kelas sudah ada.',
            'harga_kelas.required' => 'Harga kelas wajib diisi.',
            'hari_kelas.required'  => 'Pilih minimal satu hari.',
            'jam_mulai.required'   => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'guru_id.required'     => 'Guru wajib dipilih.',
        ]);

        $hariDipilih  = $validated['hari_kelas'];
        $jamMulai     = $validated['jam_mulai'];
        $jamSelesai   = $validated['jam_selesai'];

        $konflik = Kelas::all()->filter(function ($k) use ($hariDipilih, $jamMulai, $jamSelesai) {
            $hariKelas = collect(explode(',', $k->hari_kelas))->map(fn($h) => trim($h))->toArray();
            $hariSama  = count(array_intersect($hariDipilih, $hariKelas)) > 0;
            if (!$hariSama) return false;
            return $jamMulai < $k->jam_selesai && $jamSelesai > $k->jam_mulai;
        });

        if ($konflik->isNotEmpty()) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Jadwal bentrok dengan kelas "' . $konflik->first()->nama_kelas . '" di hari yang sama.',
            ]);
        }

        $kelas = Kelas::create([
            'nama_kelas'  => $validated['nama_kelas'],
            'harga_kelas' => $validated['harga_kelas'],
            'hari_kelas'  => implode(', ', $validated['hari_kelas']),
            'jam_mulai'   => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'guru_id'     => $validated['guru_id'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
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
        $kelas = Kelas::withCount(['peserta' => fn($q) => $q->where('peserta.status', 'aktif')])->findOrFail($id);
        $kelas->jumlah_peserta = $kelas->peserta_count;
        $guruList = Guru::orderBy('nama')->get();

        return view('admin.kelas.edit', compact('kelas', 'guruList'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'nama_kelas'  => 'required|string|max:255|unique:kelas,nama_kelas,' . $id,
            'harga_kelas' => 'required|numeric|min:0',
            'hari_kelas'  => 'required|array|min:1',
            'hari_kelas.*'=> 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|string',
            'jam_selesai' => 'required|string',
            'guru_id'     => 'required|exists:guru,id',
            'deskripsi'   => 'nullable|string',
        ]);

        $hariDipilih = $validated['hari_kelas'];
        $jamMulai    = $validated['jam_mulai'];
        $jamSelesai  = $validated['jam_selesai'];

        $konflik = Kelas::where('id', '!=', $id)->get()->filter(function ($k) use ($hariDipilih, $jamMulai, $jamSelesai) {
            $hariKelas = collect(explode(',', $k->hari_kelas))->map(fn($h) => trim($h))->toArray();
            $hariSama  = count(array_intersect($hariDipilih, $hariKelas)) > 0;
            if (!$hariSama) return false;
            return $jamMulai < $k->jam_selesai && $jamSelesai > $k->jam_mulai;
        });

        if ($konflik->isNotEmpty()) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Jadwal bentrok dengan kelas "' . $konflik->first()->nama_kelas . '" di hari yang sama.',
            ]);
        }

        $kelas->update([
            'nama_kelas'  => $validated['nama_kelas'],
            'harga_kelas' => $validated['harga_kelas'],
            'hari_kelas'  => implode(', ', $validated['hari_kelas']),
            'jam_mulai'   => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'guru_id'     => $validated['guru_id'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
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
        $kelas = Kelas::withCount(['peserta' => fn($q) => $q->where('peserta.status', 'aktif')])->findOrFail($id);

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
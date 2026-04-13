<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Kelas;
use App\Models\Tagihan;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KasirPesertaController extends Controller
{

    public function index()
    {
        $peserta   = Peserta::with('kelas')->get();
        $kelasList = Kelas::all();

        return view('kasir.peserta.index', compact('peserta', 'kelasList'));
    }

    public function add()
    {
        $kelasList = Kelas::all();

        return view('kasir.peserta.add', compact('kelasList'));
    }

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
            'level'         => $request->level,
            'nama_ortu'     => $request->nama_ortu,
            'no_ortu'       => $request->no_ortu,
            'status'        => 'aktif',
        ]);

        $peserta->kelas()->sync($request->kelas);
        $kelasBaru    = Kelas::whereIn('id', $request->kelas)->get();
        $totalTagihan = $kelasBaru->sum('harga_kelas');
        $bulanIni     = Carbon::now()->format('Y-m');

        Tagihan::create([
            'peserta_id'          => $peserta->id,
            'total_tagihan'       => $totalTagihan,
            'bulan_tahun'         => $bulanIni,
            'kelas_snapshot'      => $kelasBaru->pluck('nama_kelas')->toArray(),
            'tanggal_tagihan'     => Carbon::now()->startOfMonth(),
            'tanggal_jatuh_tempo' => Carbon::now()->endOfMonth(),
            'status'              => 'belum_bayar',
        ]);
        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Tambah peserta baru: ' . $peserta->nama . ' (tagihan bulan ' . $bulanIni . ' otomatis dibuat)',
        ]);

        return redirect()->route('kasir.peserta.index')
            ->with('success', "Peserta {$peserta->nama} berhasil ditambahkan beserta tagihan bulan ini.");
    }

    public function edit($id)
    {
        $peserta   = Peserta::with('kelas')->findOrFail($id);
        $kelasList = Kelas::all();

        return view('kasir.peserta.edit', compact('peserta', 'kelasList'));
    }

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

        $peserta = Peserta::with('kelas')->findOrFail($id);
        $kelasLamaIds  = $peserta->kelas->pluck('id')->toArray();
        $kelasBaruIds  = array_map('intval', $request->kelas);
        $kelasTambahan = array_diff($kelasBaruIds, $kelasLamaIds);
        $adaPerubahan  = !empty(array_diff($kelasBaruIds, $kelasLamaIds))
                      || !empty(array_diff($kelasLamaIds, $kelasBaruIds));
        $peserta->update([
            'nama'          => $request->nama,
            'no_hp'         => $request->no_hp,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'level'         => $request->level,
            'nama_ortu'     => $request->nama_ortu,
            'no_ortu'       => $request->no_ortu,
        ]);

        $peserta->kelas()->sync($request->kelas);

        $pesanTagihan = '';
        if ($adaPerubahan) {
            $bulanIni = Carbon::now()->format('Y-m');

            $tagihanBelumLunas = Tagihan::where('peserta_id', $peserta->id)
                ->where('bulan_tahun', $bulanIni)
                ->where('status', '!=', 'lunas')
                ->first();

            if ($tagihanBelumLunas) {
                $kelasAktif = Kelas::whereIn('id', $kelasBaruIds)->get();
                $totalBaru  = $kelasAktif->sum('harga_kelas');

                if ($totalBaru > 0) {
                    $tagihanBelumLunas->update([
                        'total_tagihan'  => $totalBaru,
                        'kelas_snapshot' => $kelasAktif->pluck('nama_kelas')->toArray(),
                    ]);
                    $pesanTagihan = " Tagihan bulan ini diperbarui menjadi Rp " . number_format($totalBaru, 0, ',', '.') . ".";
                } else {
                    $tagihanBelumLunas->delete();
                    $pesanTagihan = " Tagihan bulan ini dihapus karena tidak ada kelas aktif.";
                }

            } elseif (!empty($kelasTambahan)) {
                $adaTagihanLunas = Tagihan::where('peserta_id', $peserta->id)
                    ->where('bulan_tahun', $bulanIni)
                    ->where('status', 'lunas')
                    ->exists();

                $kelasObjTambahan = Kelas::whereIn('id', $kelasTambahan)->get();
                $hargaTambahan    = $kelasObjTambahan->sum('harga_kelas');
                $namaKelasTambah  = $kelasObjTambahan->pluck('nama_kelas')->implode(', ');

                if ($adaTagihanLunas) {
                    Tagihan::create([
                        'peserta_id'          => $peserta->id,
                        'total_tagihan'       => $hargaTambahan,
                        'bulan_tahun'         => $bulanIni,
                        'kelas_snapshot'      => $kelasObjTambahan->pluck('nama_kelas')->toArray(),
                        'tanggal_tagihan'     => Carbon::now(),
                        'tanggal_jatuh_tempo' => Carbon::now()->endOfMonth(),
                        'status'              => 'belum_bayar',
                    ]);
                    $pesanTagihan = " Tagihan baru dibuat untuk kelas tambahan: {$namaKelasTambah} (Rp " . number_format($hargaTambahan, 0, ',', '.') . ").";
                } else {
                    $kelasAktif      = Kelas::whereIn('id', $kelasBaruIds)->get();
                    $totalSemuaKelas = $kelasAktif->sum('harga_kelas');
                    Tagihan::create([
                        'peserta_id'          => $peserta->id,
                        'total_tagihan'       => $totalSemuaKelas,
                        'bulan_tahun'         => $bulanIni,
                        'kelas_snapshot'      => $kelasAktif->pluck('nama_kelas')->toArray(),
                        'tanggal_tagihan'     => Carbon::now()->startOfMonth(),
                        'tanggal_jatuh_tempo' => Carbon::now()->endOfMonth(),
                        'status'              => 'belum_bayar',
                    ]);
                    $pesanTagihan = " Tagihan bulan ini otomatis dibuat.";
                }
            }
        }
        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Edit data peserta: ' . $peserta->nama . ($pesanTagihan ?: ''),
        ]);

        return redirect()->route('kasir.peserta.index')
            ->with('success', "Data {$peserta->nama} berhasil diperbarui.{$pesanTagihan}");
    }

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
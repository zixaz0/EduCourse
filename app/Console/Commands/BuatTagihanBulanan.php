<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peserta;
use App\Models\Tagihan;
use Carbon\Carbon;

class BuatTagihanBulanan extends Command
{
    protected $signature   = 'tagihan:buat-bulanan';
    protected $description = 'Otomatis buat tagihan baru untuk semua peserta aktif tiap bulan';

    public function handle()
    {
        $bulanIni        = Carbon::now()->format('Y-m');
        $tanggalTagihan  = Carbon::now()->startOfMonth();    
        $tanggalJatuhTempo = Carbon::now()->endOfMonth();       

        $pesertaList = Peserta::where('status', 'aktif')
            ->with('kelas')
            ->get();

        $dibuat        = 0;
        $dilewati      = 0;
        $tidakAdaKelas = 0;

        foreach ($pesertaList as $peserta) {
            if ($peserta->kelas->isEmpty()) {
                $tidakAdaKelas++;
                continue;
            }

            $sudahAda = Tagihan::where('peserta_id', $peserta->id)
                ->where('bulan_tahun', $bulanIni)
                ->exists();

            if ($sudahAda) {
                $dilewati++;
                continue;
            }

            $totalTagihan = $peserta->kelas->sum('harga_kelas');

            Tagihan::create([
                'peserta_id'          => $peserta->id,
                'total_tagihan'       => $totalTagihan,
                'bulan_tahun'         => $bulanIni,
                'tanggal_tagihan'     => $tanggalTagihan,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'status'              => 'belum_bayar',
            ]);

            $dibuat++;
        }

        $this->info("Selesai!");
        $this->info("✅ Tagihan dibuat     : {$dibuat}");
        $this->info("⏭️  Sudah ada          : {$dilewati}");
        $this->info("⚠️  Tidak ada kelas   : {$tidakAdaKelas}");
    }
}
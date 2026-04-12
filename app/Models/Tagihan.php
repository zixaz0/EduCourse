<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $fillable = [
        'peserta_id',
        'total_tagihan',
        'bulan_tahun',
        'kelas_snapshot',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'status',
    ];

    protected $casts = [
        'status'         => 'string',
        'kelas_snapshot' => 'array', // otomatis encode/decode JSON
    ];

    // Relasi ke Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    // Relasi ke Transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'tagihan_id');
    }
}
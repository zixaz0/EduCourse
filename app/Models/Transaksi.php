<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'tagihan_id',
        'peserta_id',
        'nomor_unik',
        'uang_bayar',
        'uang_kembali',
        'user_id',
    ];

    // Relasi ke Tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    // Relasi ke Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    // Relasi ke User (kasir)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
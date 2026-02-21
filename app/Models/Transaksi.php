<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'id_tagihan',
        'id_peserta',
        'nomor_unik',
        'uang_bayar',
        'uang_kembali',
        'id_user',
    ];

    // Relasi ke Tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan');
    }

    // Relasi ke Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta');
    }

    // Relasi ke User (kasir)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'tagihan_id',
        'nomor_unik',
        'uang_bayar',
        'uang_kembalian',
        'user_id',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
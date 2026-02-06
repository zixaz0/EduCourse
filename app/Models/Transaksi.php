<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'id_produk',
        'id_peserta',
        'nomor_unik',
        'uang_bayar',
        'uang_kembali',
        'id_user'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
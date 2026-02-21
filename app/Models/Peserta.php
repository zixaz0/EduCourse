<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $table = 'peserta';

    protected $fillable = [
        'nama',
        'email',
        'kelas',
        'no_hp',
        'nama_orangtua',
        'no_orangtua',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relasi ke Tagihan
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_peserta');
    }

    // Relasi ke Transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_peserta');
    }

    // Relasi Many-to-Many ke Kelas
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'peserta_kelas', 'id_peserta', 'id_kelas');
    }
}
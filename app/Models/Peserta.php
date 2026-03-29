<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $table = 'peserta';

    protected $fillable = [
    'nama',
    'no_hp', 
    'email', 
    'jenis_kelamin',
    'kelas_akademik', 
    'nama_ortu', 
    'no_ortu', 
    'status',
];

    protected $casts = [
        'status' => 'string',
    ];

    // Relasi ke Tagihan
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'peserta_id');
    }

    // Relasi ke Transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'peserta_id');
    }

    // Relasi Many-to-Many ke Kelas
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'peserta_kelas', 'peserta_id', 'kelas_id');
    }
}
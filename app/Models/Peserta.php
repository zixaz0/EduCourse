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
    'level', 
    'nama_ortu', 
    'no_ortu', 
    'status',
];

    protected $casts = [
        'status' => 'string',
    ];

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'peserta_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'peserta_id');
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'peserta_kelas', 'peserta_id', 'kelas_id');
    }
}
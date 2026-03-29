<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'harga_kelas',
        'hari_kelas',
    ];

    // Relasi Many-to-Many ke Peserta

    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'peserta_kelas', 'kelas_id', 'peserta_id');
    }
}
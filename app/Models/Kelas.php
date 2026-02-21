<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'id_user',
        'nama_kelas',
        'harga_kelas',
        'hari_kelas',
    ];

    // Relasi ke User (admin pembuat)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi Many-to-Many ke Peserta
    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'peserta_kelas', 'id_kelas', 'id_peserta');
    }
}
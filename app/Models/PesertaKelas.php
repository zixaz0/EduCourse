<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaKelas extends Model
{
    protected $table = 'peserta_kelas';

    protected $fillable = [
        'peserta_id',
        'kelas_id',
    ];

    // Relasi ke Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
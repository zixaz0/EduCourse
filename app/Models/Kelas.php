<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'harga_kelas',
        'jam_mulai',
        'jam_selesai',
        'hari_kelas',
        'guru_id',
        'deskripsi',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'peserta_kelas');
    }
}
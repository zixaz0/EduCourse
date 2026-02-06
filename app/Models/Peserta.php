<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'nama',
        'email',
        'kelas',
        'no_hp',
        'nama_orangtua',
        'no_orangtua'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_peserta');
    }
}
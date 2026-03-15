<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'nama',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'string',
        'role'   => 'string',
    ];

    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }

    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'user_id');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'user_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }
}
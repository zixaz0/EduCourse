<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'email',
        'status',
        'role'
    ];

    protected $hidden = [
        'password'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }

    public function log()
    {
        return $this->hasMany(Log::class, 'id_user');
    }
}
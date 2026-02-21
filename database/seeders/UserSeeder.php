<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'owner',
                'email'    => 'owner@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'status'   => 'aktif',
            ],
            [
                'username' => 'admin',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'status'   => 'aktif',
            ],
            [
                'username' => 'kasir',
                'email'    => 'kasir@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'kasir',
                'status'   => 'aktif',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
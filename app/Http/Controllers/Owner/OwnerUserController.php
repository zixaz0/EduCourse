<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;

class OwnerUserController extends Controller
{
    public function index()
    {
        // Ambil semua user kecuali owner, urutkan username
        $users = User::whereNotIn('role', ['owner'])
            ->orderBy('username')
            ->get();

        return view('owner.users.index', compact('users'));
    }
}
<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;

class OwnerLogController extends Controller
{
    public function index()
    {
        // Ambil semua log dengan relasi user, terbaru di atas
        $logs = Log::with('user')
            ->latest()
            ->get();

        // Dropdown filter semua user
        $userList = User::orderBy('username')->get();

        return view('owner.log.index', compact('logs', 'userList'));
    }
}
<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;

class OwnerLogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')
            ->latest()
            ->get();

        $userList = User::orderBy('username')->get();

        return view('owner.log.index', compact('logs', 'userList'));
    }
}
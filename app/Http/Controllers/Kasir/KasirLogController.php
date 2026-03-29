<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class KasirLogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('kasir.log.index', compact('logs'));
    }
}
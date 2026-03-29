<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->get('per_page'), [5, 10, 25, 50]) ? (int) $request->get('per_page') : 10;

        $logs = Log::with('user')
                   ->where('user_id', Auth::id())
                   ->latest()
                   ->paginate($perPage)
                   ->withQueryString();

        $totalAktivitas   = Log::where('user_id', Auth::id())->count();
        $aktivitasHariIni = Log::where('user_id', Auth::id())->whereDate('created_at', today())->count();

        return view('admin.log.index', compact('logs', 'perPage', 'totalAktivitas', 'aktivitasHariIni'));
    }
}
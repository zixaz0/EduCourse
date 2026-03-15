<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * Tampilkan semua log aktivitas.
     */
    public function index(Request $request)
    {
        $query = Log::with('user');

        // Filter opsional berdasarkan user_id
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $logs,
        ]);
    }

    /**
     * Simpan log aktivitas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aktivitas' => 'required|string|max:500',
        ]);

        $log = Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => $validated['aktivitas'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log berhasil disimpan.',
            'data'    => $log->load('user'),
        ], 201);
    }

    /**
     * Tampilkan detail satu log.
     */
    public function show($id)
    {
        $log = Log::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $log,
        ]);
    }

    /**
     * Hapus log.
     */
    public function destroy($id)
    {
        $log = Log::findOrFail($id);
        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Log berhasil dihapus.',
        ]);
    }

    /**
     * Hapus semua log (clear all).
     */
    public function destroyAll()
    {
        Log::truncate();

        return response()->json([
            'success' => true,
            'message' => 'Semua log berhasil dihapus.',
        ]);
    }
}
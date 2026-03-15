<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Tampilkan semua user.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter opsional berdasarkan role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Filter opsional berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->get();

        return response()->json([
            'success' => true,
            'data'    => $users,
        ]);
    }

    /**
     * Buat user baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|string|in:admin,kasir',
            'status'   => 'required|string|in:aktif,nonaktif',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat.',
            'data'    => $user,
        ], 201);
    }

    /**
     * Tampilkan detail satu user.
     */
    public function show($id)
    {
        $user = User::with(['logs', 'kelas', 'transaksi'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }

    /**
     * Update data user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'sometimes|required|string|max:100|unique:users,username,' . $id,
            'nama'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|email|unique:users,email,' . $id,
            'role'     => 'sometimes|required|string|in:admin,kasir',
            'status'   => 'sometimes|required|string|in:aktif,nonaktif',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diperbarui.',
            'data'    => $user->fresh(),
        ]);
    }

    /**
     * Hapus user.
     */
    public function destroy($id)
    {
        // Jangan izinkan hapus diri sendiri
        if (Auth::id() == $id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun yang sedang digunakan.',
            ], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }

    /**
     * Ganti password user.
     */
    public function gantiPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->password_lama, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }

    /**
     * Profile user yang sedang login.
     */
    public function profile()
    {
        $user = Auth::user()->load(['logs', 'kelas', 'transaksi']);

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }
}
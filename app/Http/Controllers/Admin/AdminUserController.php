<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->get('per_page'), [5, 10, 25, 50]) ? (int) $request->get('per_page') : 10;

        // Admin hanya bisa lihat & kelola kasir
        $users = User::where('role', 'kasir')
                     ->latest()
                     ->paginate($perPage)
                     ->withQueryString();

        return view('admin.users.index', compact('users', 'perPage'));
    }

    public function add()
    {
        return view('admin.users.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status'   => 'required|in:aktif,nonaktif',
        ], [
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah dipakai.',
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Role dikunci kasir, abaikan input role dari request
        $validated['role']     = 'kasir';
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Tambah kasir baru: ' . $user->username,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun kasir ' . $user->username . ' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Admin hanya boleh edit kasir
        $user = User::where('role', 'kasir')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Admin hanya boleh update kasir
        $user = User::where('role', 'kasir')->findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'status'   => 'required|in:aktif,nonaktif',
        ], [
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah dipakai.',
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Role tetap kasir, tidak bisa diubah lewat admin
        $validated['role'] = 'kasir';

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Edit kasir: ' . $user->username,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun kasir ' . $user->username . ' berhasil diperbarui.');
    }

    public function toggle($id)
    {
        if (Auth::id() == $id) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Tidak dapat mengubah status akun Anda sendiri.');
        }

        // Admin hanya boleh toggle kasir
        $user       = User::where('role', 'kasir')->findOrFail($id);
        $statusBaru = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $statusBaru]);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Ubah status kasir ' . $user->username . ' menjadi ' . $statusBaru,
        ]);

        $pesan = $statusBaru === 'aktif'
            ? 'Kasir ' . $user->username . ' berhasil diaktifkan.'
            : 'Kasir ' . $user->username . ' berhasil dinonaktifkan.';

        return redirect()->route('admin.users.index')->with('success', $pesan);
    }
}
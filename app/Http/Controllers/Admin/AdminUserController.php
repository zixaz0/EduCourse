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

        $users = User::where('role', '!=', 'owner')
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
            'role'     => 'required|in:admin,kasir',
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
            'role.required'      => 'Role wajib dipilih.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Tambah user baru: ' . $user->username . ' (' . $user->role . ')',
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User ' . $user->username . ' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:admin,kasir',
            'status'   => 'required|in:aktif,nonaktif',
        ], [
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah dipakai.',
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Edit user: ' . $user->username,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User ' . $user->username . ' berhasil diperbarui.');
    }

    public function toggle($id)
    {
        if (Auth::id() == $id) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Tidak dapat mengubah status akun Anda sendiri.');
        }

        $user       = User::findOrFail($id);
        $statusBaru = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $statusBaru]);

        Log::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Ubah status user ' . $user->username . ' menjadi ' . $statusBaru,
        ]);

        $pesan = $statusBaru === 'aktif'
            ? 'User ' . $user->username . ' berhasil diaktifkan.'
            : 'User ' . $user->username . ' berhasil dinonaktifkan.';

        return redirect()->route('admin.users.index')->with('success', $pesan);
    }
}
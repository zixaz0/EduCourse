<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'min:6'],
        ]);

        $loginInput = $request->input('login');

        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $loginInput)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Email/username atau password salah.',
            ])->withInput();
        }

        if ($user->status !== 'aktif') {
            return back()->withErrors([
                'login' => 'Akun Anda tidak aktif. Hubungi admin.',
            ])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        Log::create([
            'user_id'   => $user->id,
            'aktivitas' => 'Login ke sistem sebagai ' . $user->role,
        ]);

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'kasir':
                return redirect()->route('kasir.dashboard');
            default:
                Auth::logout();
                return redirect('/login')->withErrors([
                    'login' => 'Role tidak valid.',
                ]);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            Log::create([
                'user_id'   => $user->id,
                'aktivitas' => 'Logout dari sistem',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
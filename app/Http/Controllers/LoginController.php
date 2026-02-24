<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

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
                        'email' => 'Role tidak valid.',
                    ]);
            }
        }
             return back()->withErrors([
                'email' => 'Email atau password salah.',
                ])->withInput();
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->status && $user->due_block === null) {
                return redirect()->intended('/home');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'account_blocked' => 'Akun Anda diblokir.',
                ]);
            }
        } else {
            return redirect()->back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}

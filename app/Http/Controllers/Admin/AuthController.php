<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login hanya untuk admin
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Cari user berdasarkan email
        $user = User::where('email', $credentials['email'])->first();

        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($credentials['password'], $user->password)) {

            // Cek apakah user adalah admin
            $admin = Admin::where('user_id', $user->id)->first();

            if ($admin) {
                // Jika admin ditemukan, autentikasi user
                Auth::login($user, $remember);
                return redirect()->intended('/')->with('success', 'Anda Berhasil Login'); // Arahkan ke dashboard admin
            } else {
                return redirect()->back()->withErrors([
                    'unAdmin' => 'LOGIN DI APLIKASI ALINEA,  DONWLOAD DI PLAYSTORE GRATIS NJIR.',
                ]);
            }
        } else {
            return back()->withErrors([
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {

        $admins = User::where('role', 'admin')->with('admins')->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',

        ]);

        // Buat user baru
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin', // Set role sebagai admin
            'status' => true,
        ]);

        // Buat admin terkait dengan user_id yang sama
        Admin::create([
            'user_id' => $user->id, // Gunakan ID dari user yang baru dibuat
            'role' => 'admin', // Pastikan role sebagai admin
        ]);

        return redirect()->back()->with('success', 'Admin create successfully.');
    }
}

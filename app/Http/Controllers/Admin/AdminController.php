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

    public function destroy($id)
    {
        // Find the user by ID
        $user = User::find($id);

        if ($user) {
            // Find the related admin by user_id
            $admin = Admin::where('user_id', $id)->first();

            // If an admin is found, delete the admin record first
            if ($admin) {
                $admin->delete();
            }

            // Delete the user record
            $user->delete();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'The admin has been successfully deleted.');
        }

        // If the user is not found, redirect back with an error message
        return redirect()->back()->with('error', 'The ID was not found.');
    }

    public function update(Request $request, $id)
    {
        // Cari user berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Validasi input
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:super_admin,admin',
        ]);

        // Update data User
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->email = $validatedData['email'];
        $user->save();

        // Update data Admin
        $admin = Admin::where('user_id', $id)->first();
        if ($admin) {
            $admin->role = $validatedData['role'];
            $admin->save();

            return redirect()->back()->with('success', 'Data has been updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Unable to update the data.');
        }
    }
}

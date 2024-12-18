<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\TextUI\Configuration\Variable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        $users = User::where('role', 'user')->get(); // Retrieve only users with the 'user' role
        $title = "User Manage";
        return view('admin.users.index', compact('users', 'title'));
    }

    // Show the form for creating a new user
    public function create()
    {
        return view('admin.users.create');
    }

    // Store a newly created user
    public function store(Request $request)
    {


        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',

        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user', // Set role to 'user' for new users
            'status' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    // Show the form for editing the specified user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update the specified user
    public function update(Request $request, $id)
    {
        // Cari pengguna berdasarkan ID
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Validasi data yang diterima
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses gambar jika ada
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('profile', 'public');

            // Hapus gambar lama jika bukan default.png
            if ($user->image !== 'profile/default.png' && $user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // Tambahkan path gambar baru ke data yang akan diperbarui
            $validatedData['image'] = $imagePath;
        }

        // Perbarui data pengguna
        $user->update($validatedData);

        return redirect()->back()->with('success', 'User updated successfully.');
    }



    // Remove the specified user
    public function destroy($id)
    {
        $user = user::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function myProfile()
    {

        $user = User::where('id', Auth::id())->with('admins')->first();
        $fullname = $user->first_name . ' ' . $user->last_name;
        $title = "Profile";

        return view('admin.profile.index', compact('user', 'fullname', 'title'));
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        // Validasi input
        $request->validate([
            'oldPassword' => 'required|min:8',
            'newPassword' => 'required|min:8|confirmed',
        ]);


        // Periksa apakah password lama cocok
        if (!Hash::check($request->oldPassword, $user->password)) {
            return redirect()->back()->with('error', 'Old password is incorrect.');
        }

        // Update password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        return redirect()->to('users.myProfile');
    }
}

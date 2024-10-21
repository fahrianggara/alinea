<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Configuration\Variable;

class UserController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        $users = User::where('role', 'user')->get(); // Retrieve only users with the 'user' role
        return view('admin.users.index', compact('users'));
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
    public function update(Request $request,$id)
    {
        $user=User::find($id);
        $validatedData=$request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            // Password can be optional for updates
           
        ]);

        

        if (!$user) {

            return redirect()->route('users.index')->with('error', 'user Not Found');
        }
        $user->update($validatedData);
        return redirect()->route('users.index')->with('success', 'user updated');
    }


    // Remove the specified user
    public function destroy($id)
    {
        $user = user::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
       
}
}

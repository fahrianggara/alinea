<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ResResource;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        // Get all request data to variable
        $input = $request->all();

        // Validate that the request data is correct
        $validator = Validator::make($input, [
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Return error response if request data is incorrect
        if ($validator->fails()) {
            return response()->json(new ResResource(null, false, $validator->errors()), 422);
        }

        // Check if the user has reached the maximum number of attempts
        if (RateLimiter::tooManyAttempts($request->ip(), 5)) {
            $timer = RateLimiter::availableIn($request->ip()); // Get available minutes
            $message = "Too many login attempts. Please try again after $timer seconds.";
            return response()->json(new ResResource(null, false, $message), 429);
        }

        // Check if the username is an email or nim
        $username = filter_var($input['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';

        // Set the credentials
        $credentials = [$username => $input['username'], 'password' => $input['password']];

        // Check if the user is authenticated
        if (!auth()->attempt($credentials)) {
            RateLimiter::hit($request->ip()); // Increase the number of attempts
            $message = "Invalid username or password";
            return response()->json(new ResResource(null, false, $message), 401);
        }

        // Get the user data
        $user = User::where($username, $input['username'])->first();

        // set data user & token
        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ];

        // Return success response
        return response()->json(new ResResource($data, true, 'User login successfully'), 200);
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        // Revoke the token
        $request->user()->currentAccessToken()->delete();

        // Return success response
        return response()->json(new ResResource(null, true, 'User logout successfully'), 200);
    }

    public function register(Request $request)
    {
        // Get all request data
        $input = $request->all();

        // Validate the request data
        $validator = Validator::make($input, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Confirmed untuk password
            'nim' => 'required|string|unique:users,nim',
        ]);

        // Return error response if validation fails
        if ($validator->fails()) {
            return response()->json(new ResResource(null, false, $validator->errors()), 422);
        }

        // Create a new user
        $user = User::create([
            'image' => 'profile/default.png',
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']), // Hash the password
            'nim' => $input['nim'],
            'role' => 'user', // Default role
        ]);

        // Set the response data
        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ];

        // Return success response dengan data user baru
        return response()->json(new ResResource($data, true, 'User registered successfully'), 201);
    }

    public function updateProfile(Request $request)
    {
        // Dapatkan pengguna yang sedang login
        $user = auth()->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // Optional password update
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk file gambar
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json(new ResResource(null, false, $validator->errors()), 422);
        }

        // Perbarui data pengguna
        $dataToUpdate = [
            'first_name' => $request->input('first_name', $user->first_name),
            'last_name' => $request->input('last_name', $user->last_name),
            'email' => $request->input('email', $user->email),
        ];

        // Perbarui password jika diberikan
        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

        // Perbarui gambar jika diberikan
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('profile', 'public');

            // Jika gambar sebelumnya bukan default.png, hapus gambar lama
            if ($user->image !== 'profile/default.png') {
                Storage::disk('public')->delete($user->image);
            }

            // Perbarui data gambar
            $dataToUpdate['image'] = $imagePath;
        }

        // Simpan data ke database
        // $user->update($dataToUpdate);

        // Siapkan data respons
        $responseData = [
            'id' => $user->id,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'image_url' => asset('storage/' . $user->image), // URL gambar profil
        ];

        return response()->json(new ResResource($responseData, true, 'Profile updated successfully'), 200);
    }
}

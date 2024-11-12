<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login user api
     *
     * @param  mixed $request
     * @return void
     */
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
}

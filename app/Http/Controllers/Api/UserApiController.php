<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Dapatkan pengguna yang sedang login
        $id = auth()->id(); // Mendapatkan ID pengguna yang login
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed', // Optional password update
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk file gambar
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Siapkan data untuk diperbarui
        $dataToUpdate = [
            'first_name' => $request->input('first_name', $user->first_name),
            'last_name' => $request->input('last_name', $user->last_name),
        ];

        // Perbarui password jika diberikan
        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

        // Perbarui gambar jika diberikan
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('profile', 'public');

            // Hapus gambar lama jika bukan default.png
            if ($user->image !== 'profile/default.png' && !empty($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Perbarui data gambar
            $dataToUpdate['image'] = $imagePath;
        }

        // Simpan data ke database
        $user->update($dataToUpdate);

        // Siapkan data respons
        $responseData = [
            'id' => $user->id,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'password' => $user->password,
            'image' => $user->image,
            'image_url' => $user->image_url, // Tambahkan gambar ke respons jika perlu
        ];

        return response()->json(['data' => $responseData, 'success' => true, 'message' => 'Profile updated successfully'], 200);
    }
}

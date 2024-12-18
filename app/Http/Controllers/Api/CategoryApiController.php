<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function index()
    {
        return response()->json(new ResResource(Category::all(), true, "Categories retrieved successfully"), 200);
    }

    // Get a single category by id
    public function show($id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json(new ResResource($category, true, "Category retrieved successfully"), 200);
        }

        return response()->json(new ResResource(null, false, "Category not found"), 404);
    }

    // Create a new category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        return response()->json(new ResResource($category, true, "Category created successfully"), 201);
    }

    // Update a category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(new ResResource(null, false, "Category not found"), 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json(new ResResource($category, true, "Category updated successfully"), 200);
    }

    // Delete a category
    public function destroy($id)
    {
        // Validasi jika ID bukan angka
        if (!is_numeric($id)) {
            return response()->json(new ResResource(null, false, "Invalid Category ID format"), 422);
        }

        // Cari kategori berdasarkan ID
        $category = Category::find($id);

        // Validasi jika kategori tidak ditemukan
        if (!$category) {
            return response()->json(new ResResource(null, false, "Category not found"), 404);
        }

        try {
            // Hapus kategori dari database
            $category->delete();

            // Respons sukses
            return response()->json(new ResResource(null, true, "Category deleted successfully"), 200);
        } catch (\Exception $e) {
            // Respons jika terjadi kesalahan
            return response()->json(new ResResource(null, false, "An error occurred: " . $e->getMessage()), 500);
        }
    }
}

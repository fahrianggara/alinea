<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all(), 200);
    }

    // Get a single category by id
    public function show($id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json($category, 200);
        }

        return response()->json(['message' => 'Category not found'], 404);
    }

    // Create a new category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    // Update a category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json($category, 200);
    }

    // Delete a category
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        }

        return response()->json(['message' => 'Category not found'], 404);
        }
}

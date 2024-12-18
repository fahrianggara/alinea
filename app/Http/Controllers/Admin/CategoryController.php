<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();
        $title = "Category Manage";
        return view('admin.categories.index', compact('categories', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $category = Category::create($validatedData);
        if ($category) {
            return redirect()->route('categories')->with('success', 'category Success Added');
        } else {
            return redirect()->route('categories')->with('error', 'category Fails Added');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $category = Category::find($id);

        if (!$category) {
            return redirect()->route('categories')->with('error', 'category Not Found');
        }

        $category->update($validatedData);

        return redirect()->route('categories')->with('success', 'category Success Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Validasi jika ID bukan angka
            if (!is_numeric($id)) {
                return redirect()->route('categories')->with('error', 'Invalid Category ID format.');
            }

            // Temukan kategori berdasarkan ID
            $category = Category::find($id);

            // Validasi jika kategori tidak ditemukan
            if (!$category) {
                return redirect()->route('categories')->with('error', 'Category not found.');
            }

            // Hapus kategori
            $category->delete();

            // Redirect dengan pesan sukses
            return redirect()->route('categories')->with('success', 'Category successfully deleted.');
        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan pesan error
            return redirect()->route('categories')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}

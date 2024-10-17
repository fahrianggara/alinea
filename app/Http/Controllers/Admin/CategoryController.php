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
        $categories = Category::all();
        return view('admin.pages.category', compact('categories'));
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
            return redirect()->route('category')->with('success', 'category Success Added');
        } else {
            return redirect()->route('category')->with('error', 'category Fails Added');
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
            return redirect()->route('category')->with('error', 'category Not Found');
        }

        $category->update($validatedData);

        return redirect()->route('category')->with('success', 'category Success Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()->route('category')->with('error', 'category Not Found');
        }

        $category->delete();

        return redirect()->route('category')->with('success', 'category Success Deleted');
    }
}

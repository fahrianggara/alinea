<?php

namespace App\Http\Controllers\Api;

use App\Events\NewBookAdded;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\ResResource;

class BookApiController extends Controller
{
    public function index()
    {

        $books = Book::with('category')->latest()->get();
        return response()->json(new ResResource($books, true, "Books retrieved successfully"), 200);

    }

    // Get a single book by ID
    public function show($id)
    {
        $book = Book::find($id)->with('category')->first();

        if ($book) {
            return response()->json(new ResResource($book, true, "Book retrieved successfully"), 200);
        }

        return response()->json(new ResResource(null, false, "Book retrieved successfully"), 200);

    }

    // Create a new book
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
            'description' => 'nullable|string',
            'published_date' => 'required|date',
            'status' => 'required|in:available,borrowed',
            'category_id' => 'required|integer|exists:categories,id',
        ]);
        
        $book = Book::create($validated);
        event(new NewBookAdded($book));

        return response()->json(new ResResource($book, true, "Book created successfully"), 201);
    }

    // Update a book
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(new ResResource(null, false, "Book not found"), 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
            'description' => 'nullable|string',
            'published_date' => 'required|date',
            'status' => 'required|in:available,borrowed',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $book->update($validated);

        return response()->json(new ResResource($book, true, "Book updated successfully"), 200);
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::find($id);

        if ($book) {
            $book->delete();
            return response()->json(new ResResource(null, true, "Book deleted successfully"), 200);
        }

        return response()->json(new ResResource(null, false, "Book not found"), 404);
    }
}

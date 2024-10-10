<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Get all books
    public function index()
    {
        return response()->json(Book::all(), 200);
    }

    // Get a single book by ID
    public function show($id)
    {
        $book = Book::find($id);

        if ($book) {
            return response()->json($book, 200);
        }

        return response()->json(['message' => 'Book not found'], 404);
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

        return response()->json($book, 201);
    }

    // Update a book
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
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

        return response()->json($book, 200);
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::find($id);

        if ($book) {
            $book->delete();
            return response()->json(['message' => 'Book deleted successfully'], 200);
        }

        return response()->json(['message' => 'Book not found'], 404);
    }
}

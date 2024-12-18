<?php

namespace App\Http\Controllers\Api;

use App\Events\NewBookAdded;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $book = Book::with('category')->find($id);

        if ($book) {
            return response()->json(new ResResource($book, true, "Book retrieved successfully"), 200);
        }

        return response()->json(new ResResource(null, false, "Book ID not found"), 404);
    }


    // Create a new book
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:1',
            'published_date' => 'required|date',
            'status' => 'required|in:available,borrowed',
            'category_id' => 'required|integer|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Proses cover gambar jika ada
        $imagePath = null;
        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $imagePath = $image->store('cover', 'public');
        }

        // Simpan data buku
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->description = $request->description;
        $book->stock = $request->stock;
        $book->published_date = $request->published_date;
        $book->status = $request->status;
        $book->category_id = $request->category_id;
        $book->cover = $imagePath;
        $book->created_at = Carbon::now('Asia/Jakarta');
        $book->updated_at = Carbon::now('Asia/Jakarta');
        $book->save();
        
        event(new NewBookAdded($book));
        return response()->json(new ResResource($book, true, "Book created successfully"), 201);
    }


    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(new ResResource(null, false, "Book ID not found"), 400);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $id,
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:1',
            'published_date' => 'required|date',
            'status' => 'required|in:available,borrowed',
            'category_id' => 'required|integer|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update data buku
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->description = $request->description;
        $book->stock = $request->stock;
        $book->published_date = $request->published_date;
        $book->status = $request->status;
        $book->category_id = $request->category_id;
        $book->updated_at = Carbon::now('Asia/Jakarta');

        // Proses cover gambar jika ada
        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $imagePath = $image->store('cover', 'public');

            // Hapus gambar lama jika bukan default.png
            if ($book->cover !== 'cover-book/default.png' && !empty($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            $book->cover = $imagePath;
        }

        $book->save();

        return response()->json(new ResResource($book, true, "Book updated successfully"), 200);
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(new ResResource(null, false, "Book ID not found"), 400);
        }

        // Hapus gambar jika bukan default.png
        if ($book->cover !== 'cover-book/default.png' && !empty($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return response()->json(new ResResource(null, true, "Book deleted successfully"), 200);
    }
}

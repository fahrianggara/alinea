<?php

namespace App\Http\Controllers\Api;

use App\Events\NewBookAdded;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Book;
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $id, // ISBN unik kecuali untuk buku saat ini
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:1',
            'published_date' => 'required|date',
            'status' => 'required|in:available,borrowed',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $book = Book::findOrFail($id);

        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->description = $request->description;
        $book->stock = $request->stock;
        $book->published_date = $request->published_date;
        $book->status = $request->status;
        $book->category_id = $request->category_id;
        $book->updated_at = now();

        if ($request->has('cover') && !empty($request->cover)) {
            // Hapus gambar lama jika ada dan bukan default.png
            if ($book->cover && $book->cover !== 'cover-book/default.png' && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
    
            // Menyimpan gambar cover baru
            $imageData = $request->cover;
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = uniqid() . '.png'; // Menghasilkan nama file unik
    
            // Tentukan folder penyimpanan, di sini adalah 'cover-book'
            $folderPath = 'cover-book/';
    
            // Simpan gambar ke folder 'public/cover-book'
            Storage::disk('public')->put($folderPath . $imageName, base64_decode($image));
    
            // Simpan nama file gambar di database dengan path yang sesuai
            $book->cover = $folderPath . $imageName;
        }
        
        
        $book->save();

        return response()->json(new ResResource($book, true, "Book updated successfully"), 200);
    }


    // public function update(Request $request, $id)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'author' => 'required|string|max:255',
    //         'isbn' => 'required|string|max:20|unique:books,isbn,' . $id, // Pastikan ISBN unik kecuali untuk buku saat ini
    //         'stock' => 'required|integer|min:1',
    //         'published_date' => 'required|date',
    //         'status' => 'required|in:available,borrowed',
    //         'category_id' => 'required|exists:categories,id',
    //         'description' => 'nullable|string',
    //         'cover' => 'nullable|string', // Optional jika tidak mengganti gambar
    //     ]);

    //     // Cari buku berdasarkan ID
    //     $book = Book::findOrFail($id);

    //     // Update data buku
    //     $book->title = $request->title;
    //     $book->author = $request->author;
    //     $book->isbn = $request->isbn;
    //     $book->stock = $request->stock;
    //     $book->published_date = $request->published_date;
    //     $book->status = $request->status;
    //     $book->category_id = $request->category_id;
    //     $book->description = $request->description;

    //     // Handle penggantian gambar jika ada cover baru
    //     if ($request->has('cover') && !empty($request->cover)) {
    //         // Hapus gambar lama jika ada dan bukan default.png
    //         if ($book->cover && $book->cover !== 'cover-book/default.png' && Storage::disk('public')->exists($book->cover)) {
    //             Storage::disk('public')->delete($book->cover);
    //         }

    //         // Menyimpan gambar cover baru
    //         $imageData = $request->cover;
    //         $image = str_replace('data:image/png;base64,', '', $imageData);
    //         $image = str_replace(' ', '+', $image);
    //         $imageName = uniqid() . '.png'; // Menghasilkan nama file unik

    //         // Tentukan folder penyimpanan, di sini adalah 'cover-book'
    //         $folderPath = 'cover-book/';

    //         // Simpan gambar ke folder 'public/cover-book'
    //         Storage::disk('public')->put($folderPath . $imageName, base64_decode($image));

    //         // Simpan nama file gambar di database dengan path yang sesuai
    //         $book->cover = $folderPath . $imageName;
    //     }

    //     // Simpan perubahan ke database
    //     $book->save();

    //     // Mengembalikan respons JSON menggunakan ResResource
    //     return response()->json(
    //         new ResResource($book, true, 'Book updated successfully!'),
    //         200
    //     );
    // }


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

<?php

namespace App\Http\Controllers\admin;

use App\Events\NewBookAdded;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->latest()->get();
        $categories = Category::all();
        $title = "Book Manage";

        return view('admin.books.index', compact('books', 'categories', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'stock' => 'required|integer|min:1',
            'published_date' => 'required|date',
            'status' => 'required|in:available,borrowed',
            'category_id' => 'required|exists:categories,id',
            'cropped_image' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Simpan data buku
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->stock = $request->stock;
        $book->published_date = $request->published_date;
        $book->status = $request->status;
        $book->category_id = $request->category_id;
        $book->description = $request->description;

        // Menyimpan gambar cropped
        if ($request->has('cropped_image')) {
            $imageData = $request->cropped_image; // Mendapatkan data gambar dari input tersembunyi
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

        $book->save(); // Menyimpan buku ke database

        if ($book) {
            event(new NewBookAdded($book));
            return redirect()->back()->with('success', 'Book added successfully!');
        } else {
            return redirect()->back()->with('error', 'Book added successfully!');
        }
    }

    /**
     * Display the specified resource.
     */


    public function show(string $id)
    {
        $book = Book::with('category')->find($id);
        $categories = Category::all();
        $title = "Book Detail";
        return view('admin.books.detail-update', compact('book', 'categories', 'title'));
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
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $id,
            'stock' => 'required|integer|min:1',
            'published_date' => 'required|date',
            'status' => 'required|in:available,borrowed',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'cropped_image' => 'nullable|string', // Optional jika tidak mengganti gambar
        ]);

        // Cari buku berdasarkan ID
        $book = Book::findOrFail($id);

        // Update data buku
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->stock = $request->stock;
        $book->published_date = $request->published_date;
        $book->status = $request->status;
        $book->category_id = $request->category_id;
        $book->description = $request->description;

        // Handle penggantian gambar jika ada cropped_image baru
        if ($request->has('cropped_image') && !empty($request->cropped_image)) {
            // Hapus gambar lama jika bukan default.png
            if ($book->cover && $book->cover !== 'cover-book/default.png' && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            // Menyimpan gambar cropped baru
            $imageData = $request->cropped_image;
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = uniqid() . '.png'; // Menghasilkan nama file unik

            // Tentukan folder penyimpanan
            $folderPath = 'cover-book/';

            // Simpan gambar ke folder 'public/cover-book'
            Storage::disk('public')->put($folderPath . $imageName, base64_decode($image));

            // Simpan nama file gambar di database
            $book->cover = $folderPath . $imageName;
        }

        // Simpan perubahan ke database
        $book->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('books.show', $id)->with('success', 'Book updated successfully!');
    }

    public function destroy($id)
    {
        try {
            // Validasi jika ID bukan angka
            if (!is_numeric($id)) {
                return redirect()->back()->with('error', 'Invalid Book ID format.');
            }

            // Temukan buku berdasarkan ID
            $book = Book::find($id);

            // Validasi jika buku tidak ditemukan
            if (!$book) {
                return redirect()->back()->with('error', 'Book not found.');
            }

            // Hapus notifikasi yang berhubungan dengan buku ini
            Notification::where('book_id', $id)->delete();

            // Hapus gambar cover dari storage jika ada dan bukan default.png
            if ($book->cover && $book->cover !== 'cover-book/default.png') {
                $coverPath = 'public/' . $book->cover;
                if (Storage::exists($coverPath)) {
                    Storage::delete($coverPath);
                }
            }

            // Hapus buku dari database
            $book->delete();

            // Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Book and related notifications deleted successfully!');
        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan pesan error
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}

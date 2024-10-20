<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Invoice;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowings = Borrowing::with('user', 'book.category', 'status')->get();
        $books = Book::with('category')->get();

        return view('admin.borrowings.borrowing', compact(['borrowings', 'books']));
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
        // Validasi data input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:borrow_date',
        ]);

        // Cari buku berdasarkan book_id
        $book = Book::find($request->book_id);

        // Cek apakah stok tersedia
        if ($book->stock > 0) {
            // Menyimpan data ke tabel borrowings
            Borrowing::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'borrow_date' => $request->borrow_date,
                'return_date' => $request->return_date,
                'status_id' => '1',
            ]);

            $borrowing_id = Borrowing::latest()->first()->id;

            Invoice::create([
                'user_id' => $request->user_id,
                'borrowing_id' => $borrowing_id,
                'borrow_date' => $request->borrow_date,
                'return_date' => $request->return_date,
                'status_id' => '1',
                'issue_date' => $request->return_date,
'due_date' =>$request->return_date,
'status' =>'fined'

            ]);

            // Kurangi stok buku
            $book->stock -= 1;
            $book->save(); // Simpan perubahan stok

            // Redirect setelah menyimpan data
            return redirect()->back()->with('success', 'Borrowing record added successfully and stock updated.');
        } else {
            // Jika stok buku 0, kembalikan pesan error
            return redirect()->back()->with('error', 'Stock is unavailable for this book.');
        }
    }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

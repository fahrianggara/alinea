<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Cart;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $carts = Cart::where('user_id', auth()->id())->get();
        $borrowings = Borrowing::with(['user', 'book', 'status'])->where('user_id', auth()->id())->get();

        $invoices = Invoice::with(['user', 'borrowings.book'])
            ->where('user_id', auth()->id())
            ->get();

        $bookIds = $carts->pluck('book_id'); // Mengambil semua book_id dari collection
        $status = 'Testing Success';

        // Cari semua buku berdasarkan ID yang ada di $bookIds
        $books = Book::whereIn('id', $bookIds)->get();
        return view('admin.test.index', compact(['carts', 'books', 'status', 'borrowings', 'invoices']));
    }

    public function addCart()
    {
        $books = Book::with(['category'])->take(3)->get(); // Ambil 3 data buku secara acak

        // Ambil user_id dari user yang sedang login
        $userId = auth()->user()->id;

        // Loop melalui data buku dan tambahkan ke cart
        foreach ($books as $book) {
            // Cek apakah buku sudah ada di cart
            $cart = Cart::where('user_id', $userId)
                ->where('book_id', $book->id)
                ->first();

            if (!$cart) {
                // Tambahkan ke table carts
                Cart::create([
                    'user_id' => $userId,
                    'book_id' => $book->id,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Testing Cart Success Added');
    }

    public function deleteAll()
    {
        // Hapus semua data cart untuk user yang sedang login
        Cart::where('user_id', auth()->id())->delete();

        // Redirect atau respon sukses
        return redirect()->back()->with('success', 'All items have been removed from the cart.');
    }

    public function borrow(Request $request)
    {
        // Validasi buku yang dipilih ada di database
        $books = Book::whereIn('id', $request->book_ids)->get();

        if ($books->isEmpty()) {
            return redirect()->back()->with('error', 'No books found for borrowing!');
        }

        // Generate nomor invoice unik
        $date = Carbon::now()->format('Ymd'); // e.g., 20241024
        $no_invoice = $date . Auth::id() . rand(100, 999); // e.g., 20241024123451XXX

        // Inisiasi total amount (misalnya 10.000 per buku)
        $totalAmount = 0;

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Simpan invoice
            $invoice = Invoice::create([
                'no_invoice' => $no_invoice,
                'user_id' => Auth::id(),
                'total_amount' => 0, // Total dihitung nanti
                'status' => 'clear', // Status invoice
            ]);

            foreach ($books as $book) {
                if ($book->stock <= 0) {
                    // Jika stok tidak cukup, rollback transaksi
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Book "' . $book->title . '" is out of stock!');
                }

                // Simpan data peminjaman dengan `invoice_id`
                Borrowing::create([
                    'invoice_id' => $invoice->id, // Menggunakan invoice_id, bukan no_invoice
                    'user_id' => Auth::id(),
                    'book_id' => $book->id,
                    'borrow_date' => now(),
                    'return_date' => now()->addWeeks(1), // Contoh durasi 1 minggu
                    'status_id' => 2, // Status peminjaman
                ]);

                // Kurangi stok buku
                $book->decrement('stock');

                // Tambahkan harga buku ke total
                $totalAmount += 10000; // Contoh harga setiap buku 10.000
            }

            // Update total_amount pada invoice
            $invoice->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->back()->with('success', 'Books successfully borrowed!');
        } catch (\Exception $e) {
            // Jika ada kesalahan, rollback transaksi
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while borrowing books: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

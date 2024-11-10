<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BorrowingAPIController extends Controller
{
    /**
     * Display a listing of the borrowings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all borrowings with related user, book category, status, and invoice
        $borrowings = Borrowing::with('user', 'book.category', 'status', 'invoice')->latest()->get();

        // Return the response as a JSON
        return response()->json(['borrowings' => $borrowings], 200);
    }

    /**
     * Display the specified borrowing.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fetch the borrowing by ID with related user, book, and status
        $borrowing = Borrowing::with(['user', 'book', 'status'])->find($id);

        // If the borrowing is not found, return a 404 response
        if (!$borrowing) {
            return response()->json(['message' => 'Borrowing not found'], 404);
        }

        // Return the response as JSON
        return response()->json(['borrowing' => $borrowing], 200);
    }

    public function history()
    {

        $borrowings = Borrowing::where('user_id', Auth::id())->with('book.category')->get();

        return response()->json(
            new ResResource($borrowings, true, "History retrieved successfully"),
            200
        );
    }

    /**
     * Store a newly created borrowing in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function store(Request $request)
    // {
    //     // Validasi data permintaan
    //     $request->validate([
    //         'book_ids' => 'required|array|min:1',
    //         'book_ids.*' => 'exists:books,id',
    //         'borrow_date' => 'required|date_format:d/m/Y',
    //         'return_date' => 'nullable|date_format:d/m/Y|after_or_equal:borrow_date',
    //     ]);

    //     $date = Carbon::now()->format('Ymd');
    //     $no_invoice = $date . Auth::id() . rand(100, 999);
    //     $totalAmount = 0;

    //     try {
    //         DB::beginTransaction();

    //         $borrowDate = Carbon::createFromFormat('d/m/Y', $request->borrow_date)->format('Y-m-d');
    //         $returnDate = $request->return_date ? Carbon::createFromFormat('d/m/Y', $request->return_date)->format('Y-m-d') : null;

    //         // Generate kode QR untuk invoice
    //         $qrCode = base64_encode(QrCode::format('png')->size(300)->generate($no_invoice));

    //         // Buat invoice
    //         $invoice = Invoice::create([
    //             'no_invoice' => $no_invoice,
    //             'user_id' => Auth::id(),
    //             'qr_code' => $qrCode,
    //             'total_amount' => 0, // Total akan dihitung nanti
    //             'status' => 'clear',
    //         ]);

    //         // Ambil buku berdasarkan `book_ids`
    //         $books = Book::whereIn('id', $request->book_ids)->get();

    //         foreach ($books as $book) {
    //             if ($book->stock <= 0) {
    //                 DB::rollBack();
    //                 return response()->json(['message' => 'Book "' . $book->title . '" is out of stock!'], 400);
    //             }

    //             // Simpan data peminjaman
    //             Borrowing::create([
    //                 'invoice_id' => $invoice->id,
    //                 'user_id' => Auth::id(),
    //                 'book_id' => $book->id,
    //                 'borrow_date' => $borrowDate,
    //                 'return_date' => $returnDate,
    //                 'status_id' => 1,
    //             ]);

    //             // Kurangi stok buku
    //             $book->decrement('stock');

    //             // Tambahkan harga buku ke total
    //             $totalAmount += 10000; // Contoh harga per buku 10.000
    //         }

    //         // Update total_amount pada invoice
    //         $invoice->update(['total_amount' => $totalAmount]);

    //         DB::commit();

    //         return response()->json(['message' => 'Books successfully borrowed!', 'invoice' => $invoice], 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
    //     }
    // }






    // public function store(Request $request)
    // {
    //     // Validate incoming request data
    //     $request->validate([
    //         'book_id' => 'required|exists:books,id',
    //         'borrow_date' => 'required|date',
    //         'return_date' => 'nullable|date|after_or_equal:borrow_date',
    //     ]);

    //     $date = Carbon::now()->format('Ymd');
    //     $no_invoice = $date . Auth::id() . rand(100, 999);

    //     // Find the book by ID
    //     $book = Book::find($request->book_id);
    //     if (!$book) {
    //         return response()->json(['message' => 'Book not found'], 404);
    //     }

    //     // Check if the book is available
    //     if ($book->stock <= 0) {
    //         return response()->json(['message' => 'Book is out of stock'], 400);
    //     }

    //     // Calculate the total amount (for example, 10,000 per book)
    //     $totalAmount = 10000;

    //     try {
    //         DB::beginTransaction();

    //         $borrowDate = Carbon::createFromFormat('d/m/Y', $request->borrow_date)->format('Y-m-d');
    //         $returnDate = Carbon::createFromFormat('d/m/Y', $request->return_date)->format('Y-m-d');

    //         // Generate the QR code for the invoice
    //         $qrCode = base64_encode(QrCode::format('png')->size(300)->generate($no_invoice));

    //         // Create an invoice
    //         $invoice = Invoice::create([
    //             'no_invoice' => $no_invoice,
    //             'user_id' => Auth::id(),
    //             'qr_code' => $qrCode,
    //             'total_amount' => $totalAmount,
    //             'status' => 'clear',
    //         ]);

    //         // Create the borrowing record
    //         $borrowing = Borrowing::create([
    //             'invoice_id' => $invoice->id,
    //             'user_id' => Auth::id(),
    //             'book_id' => $book->id,
    //             'borrow_date' => $borrowDate,
    //             'return_date' => $returnDate, // Set return date to 1 week from now
    //             'status_id' => 1, // Default status of borrowing
    //         ]);

    //         // Decrement the stock of the borrowed book
    //         $book->decrement('stock');

    //         DB::commit();

    //         // Return success response
    //         return response()->json(['message' => 'Book successfully borrowed!', 'borrowing' => $borrowing], 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
    //     }
    // }

    public function store(Request $request)
    {
        // Validasi buku yang dipilih ada di database
        $books = Book::whereIn('id', $request->book_ids)->get();

        if ($books->isEmpty()) {
            return response()->json(['message' => 'No books found for borrowing!'], 404);
        }

        // Generate nomor invoice unik
        $date = Carbon::now()->format('Ymd');
        $no_invoice = $date . Auth::id() . rand(100, 999);

        // Inisiasi total amount (misalnya 10.000 per buku)
        $totalAmount = 0;

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Simpan invoice
            $qrCode = base64_encode(QrCode::format('png')->size(300)->generate($no_invoice));

            $borrowDate = Carbon::createFromFormat('d/m/Y', $request->borrow_date)->format('Y-m-d');
            $returnDate = Carbon::createFromFormat('d/m/Y', $request->return_date)->format('Y-m-d');

            $invoice = Invoice::create([
                'no_invoice' => $no_invoice,
                'qr_code' => $qrCode,
                'user_id' => Auth::id(),
                'total_amount' => 0, // Total dihitung nanti
                'status' => 'clear', // Status invoice
            ]);

            foreach ($books as $book) {
                if ($book->stock <= 0) {
                    // Jika stok tidak cukup, rollback transaksi
                    DB::rollBack();
                    return response()->json(['message' => 'Book "' . $book->title . '" is out of stock!'], 400);
                }

                // Simpan data peminjaman dengan `invoice_id`
                Borrowing::create([
                    'invoice_id' => $invoice->id, // Menggunakan invoice_id, bukan no_invoice
                    'user_id' => Auth::id(),
                    'book_id' => $book->id,
                    'borrow_date' => $borrowDate,
                    'return_date' => $returnDate, // Contoh durasi 1 minggu
                    'status_id' => 1, // Status peminjaman
                ]);

                // Kurangi stok buku
                $book->decrement('stock');

                // Tambahkan harga buku ke total
                $totalAmount += 10000; // Contoh harga setiap buku 10.000
            }

            // Update total_amount pada invoice
            $invoice->update(['total_amount' => $totalAmount]);
            $invoices = Invoice::with('borrowings.book.category')->where('id', $invoice->id)->get();
            DB::commit();
            
            return response()->json(
                new ResResource($invoices, "Books successfully borrowed!", 201)
            );

        } catch (\Exception $e) {
            // Jika ada kesalahan, rollback transaksi
            DB::rollBack();
            return response()->json(['message' => 'An error occurred while borrowing books: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified borrowing in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $request->validate([
            'return_date' => 'required|date|after_or_equal:borrow_date',
        ]);

        // Find the borrowing record by ID
        $borrowing = Borrowing::find($id);
        if (!$borrowing) {
            return response()->json(['message' => 'Borrowing not found'], 404);
        }

        // Update the return date
        $borrowing->return_date = $request->return_date;
        $borrowing->save();

        // Return success response
        return response()->json(['message' => 'Borrowing updated successfully', 'borrowing' => $borrowing], 200);
    }

    /**
     * Remove the specified borrowing from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the borrowing record by ID
        $borrowing = Borrowing::find($id);
        if (!$borrowing) {
            return response()->json(['message' => 'Borrowing not found'], 404);
        }

        // Restore the book stock
        $book = $borrowing->book;
        if ($book) {
            $book->increment('stock');
        }

        // Delete the borrowing record and associated invoice
        $invoice = Invoice::find($borrowing->invoice_id);
        if ($invoice) {
            $invoice->delete();
        }
        $borrowing->delete();

        // Return success response
        return response()->json(['message' => 'Borrowing record deleted successfully'], 200);
    }
}

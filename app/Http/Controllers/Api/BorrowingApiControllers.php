<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BorrowingApiController extends Controller
{
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


    public function store(Request $request)
    {
        // Pastikan `book_id` selalu array, meskipun inputnya tunggal
        $bookIds = is_array($request->book_id) ? $request->book_id : [$request->book_id];

        // Validasi buku yang dipilih ada di database
        $books = Book::whereIn('id', $bookIds)->get();

        if ($books->isEmpty()) {
            return response()->json(['message' => 'No books found for borrowing!'], 404);
        }

        // Validasi: cek apakah buku sudah pernah dipesan oleh user yang sama
        $existingBorrowings = Borrowing::whereIn('book_id', $bookIds)
            ->where('user_id', Auth::id())
            ->whereIn('status_id', [2]) // Status yang masih aktif (misalnya, sedang dipinjam)
            ->get();

        if ($existingBorrowings->isNotEmpty()) {
            $bookTitles = $existingBorrowings->pluck('book.title')->toArray();
            return response()->json([
                'message' => 'You have already borrowed these books: ' . implode(', ', $bookTitles),
            ], 400);
        }

        // Generate nomor invoice unik
        $date = Carbon::now()->format('Ymd');
        $no_invoice = $date . Auth::id() . rand(100, 999);

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
                    'return_date' => $returnDate,
                    'status_id' => 1, // Status peminjaman
                ]);

                // Kurangi stok buku
                $book->decrement('stock');

            }

            // Update total_amount pada invoice
            //  $invoice->update(['total_amount' => $totalAmount]);
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

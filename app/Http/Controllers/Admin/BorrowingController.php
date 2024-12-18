<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowings = Borrowing::with('user', 'book.category', 'status', 'invoice')->latest()->get();
        $books = Book::with('category')->get();
        $title = "Borrowings";

        return view('admin.borrowings.index', compact('borrowings', 'books',  'title'));
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

        $date = Carbon::now()->format('Ymd');
        $no_invoice = $date . Auth::id() . rand(100, 999);

        // Cari buku berdasarkan book_id
        $book = Book::find($request->book_id);
        if (!$book) {
            return redirect()->back()->with('error', 'Book not found!');
        }

        if ($book->stock <= 0) {
            return redirect()->back()->with('error', 'Book "' . $book->title . '" is out of stock!');
        }


        // Inisiasi total amount (misalnya 10.000 per buku)
       
        // Mulai transaksi


        try {
            DB::beginTransaction();

            $qrCode = base64_encode(QrCode::format('png')->size(300)->generate($no_invoice));

            // Simpan invoice
            $invoice = Invoice::create([
                'no_invoice' => $no_invoice,
                'user_id' => Auth::id(),
                'qr_code' => $qrCode,
                'status' => 'pending', // Status invoice
            ]);

            // Simpan data peminjaman dengan `invoice_id`
            Borrowing::create([
                'invoice_id' => $invoice->id, // Menggunakan invoice_id, bukan no_invoice
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'borrow_date' => now(),
                'return_date' => now()->addWeeks(1), // Contoh durasi 1 minggu
                'status_id' => 1, // Status peminjaman
            ]);

            // Kurangi stok buku
            $book->decrement('stock');

            DB::commit();

            return redirect()->back()->with('success', 'Book successfully borrowed!');
        } catch (\Exception $e) {
            // Jika ada kesalahan, rollback transaksi
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while borrowing the book: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $borrowing = Borrowing::with(['user', 'book', 'status'])->find($id);

        return view('admin.borrowings.invoice', compact('borrowing'));
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
        $borrowing = Borrowing::find($id);
        $invoice = Invoice::where('no_invoice', $borrowing->no_invoice)->first();
        if ($invoice) {
            $invoice->delete();
        }
        $borrowing->delete();

        if ($borrowing) {

            return redirect()->back()->with('success', 'Borrowings success delete');
        } else {

            return redirect()->back()->with('error', 'Borrowings failed delete');
        }
    }
}

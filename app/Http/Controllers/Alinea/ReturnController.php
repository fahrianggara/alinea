<?php

namespace App\Http\Controllers\Alinea;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    //

    public function index()
    {
        return view('alinea.returns.index');
    }

    public function show(string $no_invoice)
    {
        // Ambil invoice berdasarkan nomor invoice dengan relasi borrowings, book, dan category
        $invoice = Invoice::with('borrowings.book.category', 'user')->where('no_invoice', $no_invoice)->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found!');
        }

        // Ambil data admin yang sedang login
        $admin = User::find(Auth::id());
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found!');
        }
        $fullnameAdmin = $admin->first_name . ' ' . $admin->last_name;

        // Ambil data user peminjam dan borrowings
        $fullname = $invoice->user->first_name . ' ' . $invoice->user->last_name;
        $borrowings = Borrowing::where('invoice_id', $invoice->id)->get();

        // Validasi status borrowings
        if ($borrowings->isEmpty()) {
            return redirect()->route('alinea')->with('error', 'No borrowings found for this invoice.');
        }

        // Hitung denda berdasarkan status borrowings dan update total_amount
        $totalFine = 0;  // Inisialisasi total denda

        foreach ($borrowings as $borrowing) {
            if ($borrowing->status_id == 4) {
                // Late return: denda per buku
                $totalFine += 20000; // 20.000 per buku
            } elseif ($borrowing->status_id == 5 || $borrowing->status_id == 6) {
                // Book Missing or Book Damaged: denda per buku
                $totalFine += 100000; // 100.000 per buku
            }
        }

        // Update total_amount di invoice dengan total denda
        $invoice->total_amount = $totalFine;
        $invoice->save();

        // Kirim data ke view
        return view('alinea.returns.detail', compact('invoice', 'fullname', 'borrowings', 'fullnameAdmin'));
    }

    public function updateStatus(Request $request, Borrowing $borrowing)
    {
        // Validasi input status_id
        $request->validate([
            'status_id' => 'required|in:5,6', // hanya status tertentu yang diizinkan
        ]);

        // Cek apakah status baru berbeda dengan status lama
        if ($borrowing->status_id == $request->status_id) {
            return redirect()->back()->with('error', 'Status is already set to the selected value.');
        }

        // Update status borrowing
        $oldStatusId = $borrowing->status_id; // Simpan status lama untuk proses denda
        $borrowing->status_id = $request->status_id;
        $borrowing->save();

        // Hitung denda dan update total_amount di invoice jika perlu
        $this->updateFineAndTotalAmount($borrowing, $oldStatusId);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Book status updated successfully!');
    }

    private function updateFineAndTotalAmount(Borrowing $borrowing, $oldStatusId)
    {
        // Menghitung denda berdasarkan status
        $fine = 0;

        // Jika status sebelumnya adalah 4 (late return), tambahkan denda 20.000
        if ($oldStatusId == 4) {
            $fine += 20000; // Denda untuk keterlambatan
        }

        // Jika status baru adalah 5 (Book Missing) atau 6 (Book Damaged), tambahkan denda 100.000
        if ($borrowing->status_id == 5 || $borrowing->status_id == 6) {
            $fine += 100000; // Denda untuk buku hilang atau rusak
        }

        // Update total fine di invoice
        $invoice = $borrowing->invoice;
        $invoice->total_amount += $fine; // Menambahkan denda ke total_amount
        $invoice->save();
    }

    public function success($id)
    {
        // Ambil data invoice dengan relasi borrowings dan user
        $invoice = Invoice::with('borrowings.book', 'user')->findOrFail($id);

        // Update status invoice menjadi clear
        $invoice->status = 'clear';
        $invoice->save();

        // Perbarui status borrowings dan tambahkan stok jika status menjadi 3
        foreach ($invoice->borrowings as $borrowing) {
            if ($borrowing->status_id == 4) {
                $borrowing->status_id = 3; // Ganti ke status 3
                $borrowing->save(); // Simpan perubahan

                // Tambahkan stok buku terkait
                $borrowing->book->stock += 1;
                $borrowing->book->save();
            }
        }

        // Gabungkan nama depan dan nama belakang
        $fullname = $invoice->user->first_name . ' ' . $invoice->user->last_name;

        return view('alinea.returns.success', compact('invoice', 'fullname'));
    }
}

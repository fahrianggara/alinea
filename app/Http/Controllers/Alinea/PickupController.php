<?php

namespace App\Http\Controllers\Alinea;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PickupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('alinea.pickups.index');
    }

    /**
     * Menampilkan detail invoice berdasarkan nomor invoice.
     */
    public function show(string $no_invoice)
    {
        // Ambil invoice berdasarkan nomor invoice dengan relasi user dan borrowings
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
        $borrowing = Borrowing::where('invoice_id', $invoice->id)->first();

        // Validasi status borrowings
        // if ($borrowing && $borrowing->status_id == 2) {
        //     return redirect()->route('alinea')->with('error', 'Invoice status is not valid for this action.');
        // }

        // Kirim data ke view
        return view('alinea.pickups.detail', compact('invoice', 'fullname', 'borrowing', 'fullnameAdmin'));
    }

    /**
     * Memproses pengambilan buku berdasarkan ID invoice.
     */
    public function pickup($id)
    {
        // Ambil data borrowings dan invoice berdasarkan ID
        $borrowings = Borrowing::where('invoice_id', $id)->with('book', 'status')->get();
        $invoice = Invoice::find($id);

        // Cek apakah data borrowings atau invoice tidak ditemukan
        if ($borrowings->isEmpty() || !$invoice) {
            return redirect()->back()->with('error', 'Borrowings or invoice not found!');
        }

        try {
            // Update status invoice menjadi 'clear'
            $invoice->status = 'clear';
            $invoice->save();

            // Update status setiap borrowing menjadi status ID 2 (borrowed)
            foreach ($borrowings as $borrowing) {
                $borrowing->status_id = 2;
                $borrowing->save();
            }

            // Redirect ke halaman sukses
            return redirect()->route('pickups.success', ['id' => $invoice->id])
                ->with('success', 'The book has been successfully borrowed.');
        } catch (\Throwable $th) {
            // Log error untuk debugging
            Log::error('Error in pickup method: ' . $th->getMessage());

            // Redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'The book borrowing process failed.');
        }
    }

    /**
     * Menampilkan halaman sukses setelah buku berhasil diproses.
     */
    public function success($id)
    {
        // Ambil data invoice dengan relasi borrowings dan user
        $invoice = Invoice::with('borrowings.book', 'user')->findOrFail($id);
        $fullname = $invoice->user->first_name. '' .$invoice->user->last_name; 
        return view('alinea.pickups.success', compact('invoice','fullname'));
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {

        $invoices = Invoice::with(['user'])->latest()->get();
        $title = "Invoices";
        return view('admin.invoices.index', compact('invoices', 'title'));
    }

    public function show($no_invoice)
    {
        $invoice = Invoice::with('borrowings.book.category', 'user')->where('no_invoice', $no_invoice)->first();
        $fullname = $invoice->user->first_name . ' ' . $invoice->user->last_name;
        $borrowing = Borrowing::where('invoice_id', $invoice->id)->first();

        if ($invoice) {
            return view('admin.invoices.pdf.invoicePdf', compact('invoice', 'fullname', 'borrowing'));
        }

        return redirect()->back()->with('error', 'Invoice not found');
    }

    public function downloadPdf($id)
    {
        // Cari data invoice
        $invoice = Invoice::with('borrowings.book.category', 'user')->where('id', $id)->first();
        $fullname = $invoice->user->first_name . ' ' . $invoice->user->last_name;
        $borrowing = Borrowing::where('invoice_id', $invoice->id)->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Generate PDF dari view
        $pdf = Pdf::loadView('admin.invoices.pdf.invoicePdf', compact('invoice', 'fullname', 'borrowing'));

        // Mengembalikan file PDF sebagai download
        return $pdf->download("invoice_{$invoice->no_invoice}_{$fullname}.pdf");
    }

    public function destroy($id)
    {
        // Cari data invoice berdasarkan id
        $invoice = Invoice::find($id);

        // Jika data invoice ditemukan
        if ($invoice) {
            // Hapus semua borrowing yang terkait dengan invoice ini
            $invoice->borrowings()->delete();

            // Hapus invoice itu sendiri
            $invoice->delete();

            return redirect()->back()->with('success', 'Invoice and related borrowings deleted successfully');
        }

        return redirect()->back()->with('error', 'Invoice not found');
    }
    public function destroyAll()
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil semua data invoice
            $invoices = Invoice::all();

            // Hapus setiap invoice beserta borrowings-nya
            foreach ($invoices as $invoice) {
                $invoice->borrowings()->delete(); // Hapus semua borrowings terkait
                $invoice->delete();               // Hapus invoice
            }

            // Commit transaksi jika semua proses berhasil
            DB::commit();
            return redirect()->back()->with('success', 'All invoices and related borrowings deleted successfully');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

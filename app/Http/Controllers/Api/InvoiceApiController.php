<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Borrowing;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceApiController extends Controller
{
    public function myInvoice()
    {

        $invoices = Invoice::where('user_id', Auth::id())
            ->with('borrowings.book.category', 'user')
            ->get();

        return response()->json(new ResResource($invoices, true, "Invoices retrieved successfully"), 200);
    }

    public function show($id)
    {
        $invoices = Invoice::where('id', $id)
            ->with('borrowings.book.category', 'user')
            ->get();

        return response()->json(new ResResource($invoices, true, "Invoices retrieved successfully"), 200);
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
        return $pdf->download("invoice_{$invoice->id}.pdf");
    }

    public function destroy($id)
    {
        // Cari data invoice berdasarkan id
        $invoice = Invoice::with('borrowings.book.category', 'user', 'notifications')->find($id);

        // Jika data invoice ditemukan
        if ($invoice) {
            // Hapus semua borrowing yang terkait dengan invoice ini

            foreach ($invoice->borrowings as $borrowing) {
                $borrowing->delete(); // Hapus semua borrowings terkait 
            }
            foreach ($invoice->notifications as $notification) {
                $notification->delete(); // Hapus semua notifications terkait 
            }

            // Hapus invoice itu sendiri
            $invoice->delete();

            return redirect()->back()->with('success', 'Invoice, related borrowings, and notifications deleted successfully');
        }

        return response()->json(new ResResource(null, true, "Invoices deleted successfully"), 200);
    }

}

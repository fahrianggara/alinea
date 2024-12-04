<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
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
        $invoice = Invoice::with('user', 'borrowings.book')->find($id);

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Generate PDF dari view
        $pdf = Pdf::loadView('admin.invoices.pdf.invoicePdf', compact('invoice'));

        // Mengembalikan file PDF sebagai download
        return $pdf->download("invoice_{$invoice->id}.pdf");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {

        $invoices = Invoice::with(['user'])->latest()->get();

        return view('admin.invoices.invoice', compact('invoices'));
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
}

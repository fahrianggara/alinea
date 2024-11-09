<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceApiController extends Controller
{
    public function myInvoice() {

        $invoices = Invoice::where('user_id', Auth::id())
            ->with('borrowings.book.category')
            ->get();

        return response(
            new ResResource($invoices, true, "Invoices retrieved successfully", 200),
        );
        
    }
}

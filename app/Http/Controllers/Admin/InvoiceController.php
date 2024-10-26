<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(){

        $invoices = Invoice::with(['user'])->get();

        return view('admin.invoices.invoice', compact('invoices'));
    }
}

<?php

namespace App\Http\Controllers\Alinea;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('alinea.pickups.index');
    }

    public function show(string $no_invoice)
    {   

        // $id = Invoice::where('no_invoice', $no_invoice)->first();
        $invoices = Invoice::where('no_invoice', $no_invoice)->with('user', 'borrowings.book')->get();
        if (!$invoices) {
            return redirect()->back()->with('error', 'Invoice not found!');
        }
        return view('alinea.pickups.detail', compact('invoices'));
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

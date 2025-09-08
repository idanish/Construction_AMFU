<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // index → list all invoices
    public function index()
    {
        $invoices = Invoice::with('serviceRequest')->latest()->get();
        return view('finance.invoices.index', compact('invoices'));
    }

    // create → show add form
    public function create()
{
    $requests = ServiceRequest::all(); // dropdown ke liye
    return view('finance.invoices.create', compact('requests'));
}


    // store → save new invoice
    public function store(Request $request)
{
    $validated = $request->validate([
        'request_id' => 'required|exists:service_requests,id',
        'invoice_no' => 'required|string|max:50|unique:invoices',
        'amount'     => 'required|numeric|min:0',
    ]);

    Invoice::create([
        'request_id' => $validated['request_id'],
        'invoice_no' => $validated['invoice_no'],
        'amount'     => $validated['amount'],
        'status'     => 'unpaid', // default
    ]);

    return redirect()->route('finance.invoices.index')->with('success', 'Invoice created successfully.');
}


    // edit → show edit form
    public function edit(Invoice $invoice)
    {
        $requests = ServiceRequest::all();
        return view('finance.invoices.edit', compact('invoice', 'requests'));
    }

    // update → save edited data
  // InvoiceController.php
public function update(Request $request, Invoice $invoice)
{
    $request->validate([
        'request_id' => 'required|integer',
        'invoice_no' => 'required|string|max:255',
        'amount' => 'required|numeric',
        // status ko validate karna optional, kyunki disabled hai
    ]);

    $invoice->request_id = $request->request_id;
    $invoice->invoice_no  = $request->invoice_no;
    $invoice->amount      = $request->amount;

    // Status sirf admin ke liye change karenge
    if(auth()->user()->role == 'admin' && $request->has('status')) {
        $invoice->status = $request->status;
    }

    $invoice->save();

    return redirect()->route('finance.invoices.index')->with('success', 'Invoice updated successfully!');
}


    // destroy → delete invoice
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('finance.invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $lastInvoice = Invoice::latest('id')->first();
        $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $invoice_no = 'INV-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $requests = ServiceRequest::all();

        return view('finance.invoices.create', compact('requests', 'invoice_no'));
    }

    // store → save new invoice
    public function store(Request $request)
    {
        $request->validate([
            'request_id'   => 'required|exists:service_requests,id',
            'amount'       => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'notes'        => 'nullable|string|max:65535', // ✅ TEXT support
            'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Auto generate invoice no
        $lastInvoice = Invoice::latest('id')->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $invoiceNo = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        // Default status, admin can override
        $status = (Auth::check() && Auth::user()->role === 'admin')
            ? ($request->status ?? 'Unpaid')
            : 'Unpaid';

        $data = [
            'request_id'   => $request->request_id,
            'invoice_no'   => $invoiceNo,
            'amount'       => $request->amount,
            'invoice_date' => Carbon::parse($request->invoice_date)->format('Y-m-d'),
            'status'       => $status,
            'notes'        => $request->notes ?? null,
        ];

        // File upload
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('invoices', 'public');
        }

        Invoice::create($data);

        return redirect()->route('finance.invoices.index')
                         ->with('success', 'Invoice created successfully!');
    }

    // edit → show edit form
    public function edit(Invoice $invoice)
    {
        $requests = ServiceRequest::all();
        return view('finance.invoices.edit', compact('invoice', 'requests'));
    }

    // update → save edited data
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'request_id'   => 'required|exists:service_requests,id',
            'amount'       => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'notes'        => 'nullable|string|max:65535', // ✅ TEXT support
            'attachment'   => 'nullable',
        ]);

        // Only admin can update status
        $status = (Auth::check() && Auth::user()->role === 'admin')
            ? ($request->status ?? $invoice->status)
            : $invoice->status;

        $data = [
            'request_id'   => $request->request_id,
            'amount'       => $request->amount,
            'invoice_date' => Carbon::parse($request->invoice_date)->format('Y-m-d'),
            'status'       => $status,
            'notes'        => $request->notes ?? null,
        ];

        // Attachment update (old file delete optional)
        if ($request->hasFile('attachment')) {
            // delete old file if exists
            if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
                Storage::disk('public')->delete($invoice->attachment);
            }

            $data['attachment'] = $request->file('attachment')->store('invoices', 'public');
        }

        $invoice->update($data);

        return redirect()->route('finance.invoices.index')
                         ->with('success', 'Invoice updated successfully!');
    }

    // destroy → delete invoice
    public function destroy(Invoice $invoice)
    {
        // delete attachment if exists
        if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
            Storage::disk('public')->delete($invoice->attachment);
        }

        $invoice->delete();
        return redirect()->route('finance.invoices.index')
                         ->with('success', 'Invoice deleted successfully.');
    }

// app/Http/Controllers/Finance/InvoiceController.php
public function download($id)
{
    $invoice = Invoice::findOrFail($id);

    // pdf view ko load karo
    $pdf = \PDF::loadView('finance.invoices.pdf', compact('invoice'));

    // file download ke liye
    return $pdf->download('invoice_' . $invoice->id . '.pdf');
}

}

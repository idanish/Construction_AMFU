<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource. (Filters, Search, Pagination added)
     */
    public function index(Request $r)
    {
        // 1. Pagination Setup
        $perPage = $r->input('per_page', 5);
        if (!in_array($perPage, [10, 25, 50, 100])) {
        $perPage = 10;
        }

        $invoicesQuery = Invoice::with('procurement.department')->latest(); 

        // 2. Search Logic (Invoice No, Vendor Name par)
        if (!empty($r->search)) {
        $invoicesQuery->where(function ($query) use ($r) {
            $query->where('invoice_no', 'like', '%' . $r->search . '%')
                  ->orWhere('vendor_name', 'like', '%' . $r->search . '%');
        });
    }

        // 3. Date Range Filter (Invoice Date)
        if (!empty($r->start_date) && !empty($r->end_date)) {
        $invoicesQuery->whereBetween('invoice_date', [$r->start_date, $r->end_date]);
        } elseif (!empty($r->date)) {
        $invoicesQuery->whereDate('invoice_date', $r->date);
        }
        
        // 4. Status Filter (lowercase values use kiye gaye hain)
        if (!empty($r->status) && in_array($r->status, ['unpaid', 'partial', 'paid'])) {
        $invoicesQuery->where('status', $r->status);
        }

        $invoices = $invoicesQuery->paginate($perPage);

        return view('finance.invoices.index', compact('invoices'));
    }

    // ----------------------------------------------------------------------

    public function create()
    {

        $procurements = Procurement::where('status', 'approved')
            ->whereDoesntHave('invoice')
            ->get();
        
        // Next Invoice ID se number generate karna
        $lastInvoice = Invoice::withTrashed()->latest('id')->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $invoice_no = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT); 

        return view('finance.invoices.create', compact('procurements', 'invoice_no'));
    }


    // ----------------------------------------------------------------------

    public function store(Request $r)
    {
        $procurement = Procurement::findOrFail($r->procurement_id);
        $r->validate([
            'procurement_id' => 'required|exists:procurements,id',
            'amount'         => 'required|numeric|min:0|max:' . $procurement->cost_estimate,
            'invoice_date'   => 'required|date',
            'vendor_name'    => 'required|string|max:255',
            'due_date'       => 'required|date|after_or_equal:invoice_date', // CRITICAL: Due date validation
            'notes'          => 'nullable|string|max:65535',
            'attachment'     => 'nullable|file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Invoice Number generation (last ID including soft deleted)
        $lastInvoice = Invoice::withTrashed()->latest('id')->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $invoiceNo = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $data = [
            'procurement_id' => $r->procurement_id,
            'invoice_no'     => $invoiceNo, 
            'amount'         => $r->amount,
            'invoice_date'   => Carbon::parse($r->invoice_date)->format('Y-m-d'),
            'vendor_name'    => $r->vendor_name,
            'due_date'       => Carbon::parse($r->due_date)->format('Y-m-d'),
            'status'         => 'unpaid',
            'notes'          => $r->notes ?? null,
        ];

        // Handle attachment
        if ($r->hasFile('attachment')) {
            $data['attachment'] = $r->file('attachment')->store('invoices', 'public');
        }

        Invoice::create($data);

        return redirect()->route('finance.invoices.index')->with('success', 'Invoice created successfully!');
    }

    // ----------------------------------------------------------------------

    public function edit(Invoice $invoice)
    {

        $procurements = Procurement::where('status', 'approved')
            ->orWhere('id', $invoice->procurement_id) 
            ->get();

        return view('finance.invoices.edit', compact('invoice', 'procurements'));
    }


    // ----------------------------------------------------------------------

    public function update(Request $r, Invoice $invoice)
    {
        $procurement = Procurement::findOrFail($r->procurement_id);
        $r->validate([
            'procurement_id' => 'required|exists:procurements,id',
            'amount'         => 'required|numeric|min:0|max:' . $procurement->cost_estimate,
            'invoice_date'   => 'required|date',
            'vendor_name'    => 'required|string|max:255',
            'due_date'       => 'required|date|after_or_equal:invoice_date', // Date validation
            'notes'          => 'nullable|string|max:65535',
            'attachment'     => 'nullable|file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $data = [
            'procurement_id' => $r->procurement_id,
            'amount'         => $r->amount,
            'invoice_date'   => Carbon::parse($r->invoice_date)->format('Y-m-d'),
            'vendor_name'    => $r->vendor_name,
            'due_date'       => Carbon::parse($r->due_date)->format('Y-m-d'),
            'notes'          => $r->notes ?? null,
            // Status update PaymentController se hoga
        ];

        // File Upload (replace old if exists)
        if ($r->hasFile('attachment')) {
            if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
                Storage::disk('public')->delete($invoice->attachment);
            }
            $data['attachment'] = $r->file('attachment')->store('invoices', 'public');
        }

        // Amount change hone se pehle update kar dein
        $invoice->update($data); 

        // CRITICAL FIX: Agar amount change hua hai, toh status dobara calculate karein.
        $paidTotal = $invoice->payments()->sum('amount'); 

        if ($paidTotal >= $invoice->amount) {
            $invoice->update(['status' => 'paid']); // lowercase
        } elseif ($paidTotal > 0) {
            $invoice->update(['status' => 'partial']); // lowercase
        } else {
            $invoice->update(['status' => 'unpaid']); // lowercase
        }

        return redirect()
            ->route('finance.invoices.index')
            ->with('success', 'Invoice updated successfully!');
    }

    // ----------------------------------------------------------------------

    public function destroy(Invoice $invoice)
    {
        // Attachment delete
        if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
            Storage::disk('public')->delete($invoice->attachment);
        }

        // Soft Delete (payments bhi cascade delete ya soft delete ho sakti hain, depending on relationship)
        $invoice->delete();

        return redirect()->route('finance.invoices.index')->with('success', 'Invoice deleted successfully!');
    }

    // ----------------------------------------------------------------------
    public function show($id)
{
    $invoice = Invoice::with('procurement.department')->findOrFail($id);

    return view('finance.invoices.show', compact('invoice'));
}

public function download($id)
{
    $invoice = Invoice::findOrFail($id);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.invoices.pdf', compact('invoice'))
              ->setPaper('A4', 'portrait');

    return $pdf->download('invoice-' . $invoice->invoice_no . '.pdf');
}

    
}
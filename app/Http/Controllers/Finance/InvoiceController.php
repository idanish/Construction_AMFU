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
    public function index()
    {
        $invoices = Invoice::with('procurement.department')->get();
        return view('finance.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $user = auth()->user();

        $procurements = Procurement::with('department')
            ->where('status', 'approved')
            ->get();

        // Invoice number simple logic
        $lastInvoice = Invoice::withTrashed()->latest('id')->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;  
        $invoice_no = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return view('finance.invoices.create', compact('procurements', 'invoice_no'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'procurement_id' => 'required|exists:procurements,id',
            'amount'         => 'required|numeric|min:0',
            'invoice_date'   => 'required|date',
            'vendor_name'    => 'required|string|max:255',
            'due_date'       => 'required|date',
            'notes'          => 'nullable|string|max:65535',
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Invoice number based on ID including soft deleted
        $lastInvoice = Invoice::withTrashed()->latest('id')->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $invoiceNo = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $status = (Auth::check() && Auth::user()->role === 'admin')
            ? ($r->status ?? 'Unpaid')
            : 'Unpaid';

        $data = [
            'procurement_id' => $r->procurement_id,
            'invoice_no'     => $invoiceNo,
            'amount'         => $r->amount,
            'invoice_date'   => Carbon::parse($r->invoice_date)->format('Y-m-d'),
            'vendor_name'    => $r->vendor_name,
            'due_date'       => Carbon::parse($r->due_date)->format('Y-m-d'),
            'status'         => $status,
            'notes'          => $r->notes ?? null,
        ];

        if ($r->hasFile('attachment')) {
            $data['attachment'] = $r->file('attachment')->store('invoices', 'public');
        }

        Invoice::create($data);

        return redirect()->route('finance.invoices.index')->with('success', 'Invoice created successfully!');
    }

    public function edit(Invoice $invoice)
    {
        $procurements = Procurement::where('status', 'approved')
            ->orWhere('id', $invoice->procurement_id) // ensure current procurement always included
            ->get();

        return view('finance.invoices.edit', compact('invoice', 'procurements'));
    }

    public function update(Request $r, Invoice $invoice)
    {
        $r->validate([
            'procurement_id' => 'required|exists:procurements,id',
            'amount'         => 'required|numeric|min:0',
            'invoice_date'   => 'required|date',
            'vendor_name'    => 'required|string|max:255',
            'due_date'       => 'required|date',
            'notes'          => 'nullable|string|max:65535',
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $status = auth()->check() && auth()->user()->role === 'admin'
            ? ($r->status ?? $invoice->status)
            : $invoice->status;

        $data = [
            'procurement_id' => $r->procurement_id,
            'amount'         => $r->amount,
            'invoice_date'   => Carbon::parse($r->invoice_date)->format('Y-m-d'),
            'vendor_name'    => $r->vendor_name,
            'due_date'       => Carbon::parse($r->due_date)->format('Y-m-d'),
            'status'         => $status,
            'notes'          => $r->notes ?? null,
        ];

        // File Upload (replace old if exists)
        if ($r->hasFile('attachment')) {
            if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
                Storage::disk('public')->delete($invoice->attachment);
            }
            $data['attachment'] = $r->file('attachment')->store('invoices', 'public');
        }

        $invoice->update($data);

        return redirect()
            ->route('finance.invoices.index')
            ->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
            Storage::disk('public')->delete($invoice->attachment);
        }

        $invoice->delete();

        return redirect()->route('finance.invoices.index')->with('success', 'Invoice deleted successfully!');
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        $pdf     = Pdf::loadView('finance.invoices.pdf', compact('invoice'));

        return $pdf->download('invoice_' . $invoice->id . '.pdf');
    }
}

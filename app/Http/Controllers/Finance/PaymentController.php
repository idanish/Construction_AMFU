<?php

namespace App\Http\Controllers\Finance;

use App\Models\Payment;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // List all payments
    public function index()
    {
        $payments = Payment::with('invoice')->latest()->get();
        return view('finance.payments.index', compact('payments'));
    }

    // Show create form
    public function create()
    {
        $invoices = Invoice::all();
        $payment_ref = 'PAY-' . strtoupper(Str::random(8));
        return view('finance.payments.create', compact('invoices', 'payment_ref'));
    }

    // Store new payment
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'payment_ref'  => 'required|unique:payments,payment_ref',
            'invoice_id'   => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0',
            'method'       => 'required|in:Cash,Bank,Online',
            'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Prepare data
        $data = $request->only('payment_ref','invoice_id','payment_date','amount','method');
        $data['status'] = 'pending';

        // Handle attachment
        if ($request->hasFile('attachment')) {
            try {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/payments', $filename);
                $data['attachment'] = $filename;
            } catch (\Exception $e) {
                return back()->with('error', 'Attachment upload failed: ' . $e->getMessage())->withInput();
            }
        }

        // Insert payment
        try {
            Payment::create($data);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment could not be saved: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('finance.payments.index')
            ->with('success', 'Payment added successfully. Awaiting admin approval.');
    }

    // Show edit form
    public function edit(Payment $payment)
    {
        $invoices = Invoice::all();
        return view('finance.payments.edit', compact('payment', 'invoices'));
    }

    // Update payment
    public function update(Request $request, Payment $payment)
{
    $request->validate([
        'invoice_id'   => 'required|exists:invoices,id',
        'payment_date' => 'required|date',
        'amount'       => 'required|numeric|min:0',
        'status'       => 'required|in:pending,completed',
        'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Only the correct columns
    $data = $request->only('invoice_id', 'payment_date', 'amount', 'status');

    // Handle attachment replacement
    if ($request->hasFile('attachment')) {
        // Delete old file if exists
        if ($payment->attachment && file_exists(storage_path('app/public/payments/' . $payment->attachment))) {
            unlink(storage_path('app/public/payments/' . $payment->attachment));
        }
        $file = $request->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/payments', $filename);
        $data['attachment'] = $filename;
    }

    try {
        $payment->update($data);
    } catch (\Exception $e) {
        return back()->with('error', 'Update failed: ' . $e->getMessage())->withInput();
    }

    return redirect()->route('finance.payments.index')
        ->with('success', 'Payment updated successfully.');
}


    // Delete payment
    public function destroy(Payment $payment)
    {
        // Delete attachment
        if ($payment->attachment && file_exists(storage_path('app/public/payments/' . $payment->attachment))) {
            unlink(storage_path('app/public/payments/' . $payment->attachment));
        }

        try {
            $payment->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Payment could not be deleted: ' . $e->getMessage());
        }

        return redirect()->route('finance.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    // Show single payment
    public function show(Payment $payment)
    {
        return view('finance.payments.show', compact('payment'));
    }
}

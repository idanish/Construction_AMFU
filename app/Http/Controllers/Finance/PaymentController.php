<?php

namespace App\Http\Controllers\Finance;

use App\Models\Payment;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Finance\PaymentController;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('invoice')->latest()->get();
        return view('finance.payments.index', compact('payments'));
    }

    public function create()
    {
        $invoices = Invoice::all();
        return view('finance.payments.create', compact('invoices'));
    }

    public function store(Request $request)
{
    $request->validate([
        'invoice_id' => 'required|exists:invoices,id',
        'payment_date' => 'required|date',
        'amount' => 'required|numeric',
    ]);

    Payment::create([
        'invoice_id'   => $request->invoice_id,
        'payment_date' => $request->payment_date,
        'amount'       => $request->amount,
        'status'       => 'pending',  // ✅ hamesha pending
    ]);

    return redirect()->route('finance.payments.index')
        ->with('success', 'Payment added successfully. Awaiting admin approval.');
}


    public function edit(Payment $payment)
    {
        $invoices = Invoice::all();
        return view('finance.payments.edit', compact('payment', 'invoices'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:completed,pending',
        ]);

        $payment->update($request->all());

        return redirect()->route('finance.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('finance.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function show(Payment $payment)
{
    return view('finance.payments.show', compact('payment'));
}

}

<?php

namespace App\Http\Controllers\Finance;
use Yajra\DataTables\DataTables;
use App\Models\Payment;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
   public function index()
    {
        // simple get with invoice relation
        $payments = Payment::with('invoice')->latest()->paginate(10);

        return view('finance.payments.index', compact('payments'));
    }
    public function create()
    {
        $invoices = Invoice::all();
        $payment_ref = 'PAY-' . strtoupper(Str::random(8));
        return view('finance.payments.create', compact('invoices', 'payment_ref'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'payment_ref'  => 'required|unique:payments,payment_ref',
            'invoice_id'   => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0',
            'method'       => 'required|in:Cash,Bank,Online',
            'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $r->only('payment_ref','invoice_id','payment_date','amount','method');
        $data['status'] = 'pending';

        if ($r->hasFile('attachment')) {
            $file = $r->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/payments', $filename);
            $data['attachment'] = $filename;
        }

        $payment = Payment::create($data);

        // ✅ Update Invoice Status
        $invoice = Invoice::find($payment->invoice_id);
        $paidTotal = $invoice->payments()->sum('amount');
        if ($paidTotal >= $invoice->amount) {
            $invoice->update(['status' => 'Paid']);
        }

        return redirect()->route('finance.payments.index')
            ->with('success', 'Payment added successfully.');
    }

    public function edit(Payment $payment)
    {
        $invoices = Invoice::all();
        return view('finance.payments.edit', compact('payment', 'invoices'));
    }

    public function update(Request $r, Payment $payment)
    {
        $r->validate([
            'invoice_id'   => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0',
            'status'       => 'required|in:pending,completed',
            'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $r->only('invoice_id','payment_date','amount','status');

        if ($r->hasFile('attachment')) {
            if ($payment->attachment && file_exists(storage_path('app/public/payments/' . $payment->attachment))) {
                unlink(storage_path('app/public/payments/' . $payment->attachment));
            }
            $file = $r->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/payments', $filename);
            $data['attachment'] = $filename;
        }

        $payment->update($data);

        // ✅ Invoice auto-update
        $invoice = Invoice::find($payment->invoice_id);
        $paidTotal = $invoice->payments()->sum('amount');
        $invoice->update(['status' => $paidTotal >= $invoice->amount ? 'Paid' : 'Unpaid']);

        return redirect()->route('finance.payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->attachment && file_exists(storage_path('app/public/payments/' . $payment->attachment))) {
            unlink(storage_path('app/public/payments/' . $payment->attachment));
        }

        $payment->delete();
        return redirect()->route('finance.payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function show(Payment $payment)
    {
        return view('finance.payments.show', compact('payment'));
    }
}

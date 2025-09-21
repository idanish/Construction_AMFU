<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // PaymentController@index
public function index()
{
    $payments = Payment::with('invoice.payments')->get();

    // Calculate invoice amount, balance, and current payment
    $payments->map(function ($payment) {
        $payment->invoice_amount = $payment->invoice ? $payment->invoice->amount : 0;

        // Total paid so far (excluding current payment)
        $totalPaidBefore = $payment->invoice 
            ? $payment->invoice->payments->where('id', '!=', $payment->id)->sum('amount') 
            : 0;

        $payment->balance = $payment->invoice_amount - $totalPaidBefore - $payment->amount;
        return $payment;
    });

    return view('finance.payments.index', compact('payments'));
}


    public function create()
    {
        $invoices = Invoice::where('status', '!=', 'paid')->get(); // invoice status paid/unpaid
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
            'method'       => 'required|in:Cash,Bank,Card',
            'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $invoice = Invoice::findOrFail($r->invoice_id);
        $paidTotal = $invoice->payments()->sum('amount');
        $remaining = $invoice->amount - $paidTotal;

        if ($r->amount > $remaining) {
            return back()->withInput()->withErrors(['amount' => "Payment cannot exceed remaining invoice amount ($remaining)."]);
        }

        $data = $r->only('payment_ref','invoice_id','payment_date','amount','method');

        // Determine payment status
        $newTotal = $paidTotal + $r->amount;
       $data['status'] = $newTotal >= $invoice->amount ? 'Paid' : 'Partial';


        // Handle attachment
        if ($r->hasFile('attachment')) {
            $file = $r->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/payments', $filename);
            $data['attachment'] = $filename;
        }

        // Create payment
        $payment = Payment::create($data);

        // Update invoice status
        if ($newTotal >= $invoice->amount) {
    $invoice->update(['status' => 'Paid']);
    $payment->update(['status' => 'Paid']);
} elseif ($newTotal > 0) {
    $invoice->update(['status' => 'Partial']);
    $payment->update(['status' => 'Partial']);
} else {
    $invoice->update(['status' => 'Unpaid']);
    $payment->update(['status' => 'Unpaid']);
}
        return redirect()->route('finance.payments.index')->with('success', 'Payment added successfully.');
    }

    public function edit(Payment $payment)
    {
        $invoices = Invoice::where('status', '!=', 'paid')
            ->orWhere('id', $payment->invoice_id)
            ->get();

        return view('finance.payments.edit', compact('payment', 'invoices'));
    }

   public function update(Request $r, Payment $payment)
{
    $r->validate([
        'invoice_id'   => 'required|exists:invoices,id',
        'payment_date' => 'required|date',
        'amount'       => 'required|numeric|min:0',
        'method'       => 'required|in:Cash,Bank,Card',
        'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $invoice = Invoice::findOrFail($r->invoice_id);

    $paidTotalExcludingCurrent = $invoice->payments()->where('id', '!=', $payment->id)->sum('amount');
    $remaining = $invoice->amount - $paidTotalExcludingCurrent;

    if ($r->amount > $remaining) {
        return back()->withInput()->withErrors(['amount' => "Payment cannot exceed remaining invoice amount ($remaining)."]);
    }

    $data = $r->only('invoice_id','payment_date','amount','method');

    // Handle attachment
    if ($r->hasFile('attachment')) {
        if ($payment->attachment && Storage::disk('public')->exists('payments/'.$payment->attachment)) {
            Storage::disk('public')->delete('payments/'.$payment->attachment);
        }
        $file = $r->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/payments', $filename);
        $data['attachment'] = $filename;
    }

    $payment->update($data);

    // Update invoice and payment status
    $newTotal = $paidTotalExcludingCurrent + $r->amount;

    if ($newTotal >= $invoice->amount) {
        $invoice->update(['status' => 'paid']);
        $payment->update(['status' => 'Paid']);
    } elseif ($newTotal > 0) {
        $invoice->update(['status' => 'partial']);
        $payment->update(['status' => 'Partial']);
    } else {
        $invoice->update(['status' => 'unpaid']);
        $payment->update(['status' => 'Unpaid']);
    }

    return redirect()->route('finance.payments.index')->with('success', 'Payment updated successfully.');
}


    public function destroy(Payment $payment)
    {
        if ($payment->attachment && Storage::disk('public')->exists('payments/'.$payment->attachment)) {
            Storage::disk('public')->delete('payments/'.$payment->attachment);
        }

        $invoice = Invoice::find($payment->invoice_id);
        $payment->delete();

        // Recalculate invoice status
        if ($invoice) {
            $paidTotal = $invoice->payments()->sum('amount');
            if ($paidTotal >= $invoice->amount) {
                $invoice->update(['status' => 'paid']);
            } elseif ($paidTotal > 0) {
                $invoice->update(['status' => 'partial']);
            } else {
                $invoice->update(['status' => 'unpaid']);
            }
        }

        return redirect()->route('finance.payments.index')->with('success', 'Payment deleted successfully.');
    }
}
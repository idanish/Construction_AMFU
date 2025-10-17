<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
   
    protected function updateInvoiceStatus(Invoice $invoice): string
    {
        // Total paid amount 
        $paidTotal = $invoice->payments()->sum('amount'); 
        
        // Status determination 
        if ($paidTotal >= $invoice->amount) {
            $status = 'paid';
        } elseif ($paidTotal > 0) {
            $status = 'partial';
        } else {
            $status = 'unpaid';
        }

        $invoice->update(['status' => $status]);
        return $status;
    }


    public function index(Request $r)
    {
        
        $perPage = $r->input('per_page', 5); 
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $paymentsQuery = Payment::with('invoice.payments')->latest();

        // Search logic
        if ($r->has('search') && !empty($r->search)) {
            $paymentsQuery->where(function ($query) use ($r) {
                $query->where('payment_ref', 'like', '%' . $r->search . '%')
                      ->orWhere('amount', 'like', '%' . $r->search . '%')
                      ->orWhere('transaction_no', 'like', '%' . $r->search . '%');
            });
        }

        // Date Range Filter
        if ($r->filled('start_date') && $r->filled('end_date')) {
        $paymentsQuery->whereBetween('payment_date', [
        $r->start_date . " 00:00:00", 
        $r->end_date . " 23:59:59"
        ]);
        } elseif ($r->filled('date')) {
        $paymentsQuery->whereDate('payment_date', $r->date);
        }
        
        $payments = $paymentsQuery->paginate($perPage);

        // Balance Calculation
        $payments->getCollection()->map(function ($payment) {
            $payment->invoice_amount = $payment->invoice ? $payment->invoice->amount : 0;
            
            // Total paid before current payment 
            $totalPaidBefore = $payment->invoice 
                ? $payment->invoice->payments->where('id', '!=', $payment->id)->sum('amount') 
                : 0;

            $payment->balance = $payment->invoice_amount - ($totalPaidBefore + $payment->amount); 
            return $payment;
        });

        return view('finance.payments.index', compact('payments'));
    }

    // ----------------------------------------------------------------------

    public function create()
    {
        // Sirf 'unpaid' ya 'partial' invoices ko fetch karein
        $invoices = Invoice::whereIn('status', ['unpaid', 'partial'])->get(); 
        $payment_ref = 'PAY-' . strtoupper(Str::random(8));
        return view('finance.payments.create', compact('invoices', 'payment_ref'));
    }

    // ----------------------------------------------------------------------

    public function store(Request $r)
    {
        $r->validate([
            'payment_ref'  => 'required|unique:payments,payment_ref',
            'invoice_id'   => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0',
            'method'       => 'required|in:Cash,Bank,Online,Card',
            'transaction_no' => 'nullable|numeric',
            'attachment'     => 'nullable|file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $invoice = Invoice::findOrFail($r->invoice_id);

      
        $paymentDate = Carbon::parse($r->payment_date);


        if ($paymentDate->lt($invoice->invoice_date)) {
            return back()->withInput()->withErrors(['payment_date' => "Payment date cannot be before invoice date ({$invoice->invoice_date->format('Y-m-d')})."]);
        }
        
 
        if ($invoice->due_date && $paymentDate->gt($invoice->due_date)) {
          
        }

       
        $paidTotal = $invoice->payments()->sum('amount');
        $remaining = $invoice->amount - $paidTotal;

        if ($r->amount > $remaining) {
            return back()->withInput()->withErrors(['amount' => "Payment cannot exceed remaining invoice amount ($remaining)."]);
        }

        $data = $r->only('payment_ref','invoice_id','payment_date','amount','method','transaction_no');

      
         // Handle attachment
    if ($r->hasFile('attachment')) {
        $path = $r->file('attachment')->store('payments', 'public');
        $data['attachment'] = $path; 
    }

        // Create payment
        $payment = Payment::create($data);

        // 3. Invoice Status Update
        $this->updateInvoiceStatus($invoice);

        return redirect()->route('finance.payments.index')->with('success', 'Payment added successfully.');
    }


    public function update(Request $r, Payment $payment)
    {
        $r->validate([
            'payment_ref'    => 'required|unique:payments,payment_ref,' . $payment->id, 
            'invoice_id'     => 'required|exists:invoices,id',
            'payment_date'   => 'required|date',
            'amount'         => 'required|numeric|min:0',
            'method'         => 'required|in:Cash,Bank,Online,Card',
            'transaction_no' => 'nullable|numeric',
            'attachment'     => 'nullable|file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048', 
        ]);
        
        $invoice = Invoice::findOrFail($r->invoice_id);
        $data = $r->only('payment_ref', 'invoice_id','payment_date','amount','method','transaction_no');
        
        if ($r->hasFile('attachment')) {
            if ($payment->attachment) {
                Storage::disk('public')->delete($payment->attachment);
            }
            $path = $r->file('attachment')->store('payments', 'public');
            $data['attachment'] = $path; 
        } 
        
        $payment->update($data);

        // Invoice status update
        $this->updateInvoiceStatus($invoice); 

        return redirect()->route('finance.payments.index')->with('success', 'Payment updated successfully.');
    }

 

    public function destroy(Payment $payment)
    {
        // Attachment delete logic
       if ($payment->attachment && Storage::disk('public')->exists($payment->attachment)) {
        Storage::disk('public')->delete($payment->attachment);
    }

        $invoice = Invoice::find($payment->invoice_id);
        
        // Soft Delete
        $payment->delete(); 

        // Recalculate invoice status based on remaining payments
        if ($invoice) {
            $this->updateInvoiceStatus($invoice); 
        }

        return redirect()->route('finance.payments.index')->with('success', 'Payment deleted successfully.');
    }



/**
 * Show the form for editing the specified payment.
 *
 * @param  \App\Models\Payment  $payment
 * @return \Illuminate\Http\Response
 */
public function edit(Payment $payment)
{
    
    $invoices = Invoice::whereIn('status', ['unpaid', 'partial'])
        ->orWhere('id', $payment->invoice_id)
        ->get(); 
        
    return view('finance.payments.edit', compact('payment', 'invoices'));
}

}
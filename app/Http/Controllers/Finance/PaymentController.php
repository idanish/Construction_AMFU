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
    public function index(Request $request)
{
    if ($request->ajax()) {
        $payments = Payment::with('invoice')->latest()->get();

        return DataTables::of($payments)
            ->addIndexColumn()
            ->addColumn('invoice', function($row){
                return $row->invoice->invoice_no ?? 'N/A';
            })
            ->addColumn('amount', function($row){
                return number_format($row->amount, 2);
            })
            ->addColumn('status', function($row){
                $color = $row->status == 'completed' ? 'success' : 'warning';
                return '<span class="badge bg-'.$color.'">'.ucfirst($row->status).'</span>';
            })
            ->addColumn('attachment', function($row){
                if($row->attachment){
                    return '<a href="'.asset('storage/'.$row->attachment).'" target="_blank" class="btn btn-sm btn-info">View</a>';
                }
                return '<span class="badge bg-secondary">No File</span>';
            })
            ->addColumn('action', function($row){
                return '
                    <a href="'.route('finance.payments.edit',$row->id).'" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>
                ';
            })
            ->rawColumns(['status','attachment','action'])
            ->make(true);
    }

    return view('finance.payments.index');
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

        return redirect()->route('payments.index')
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

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->attachment && file_exists(storage_path('app/public/payments/' . $payment->attachment))) {
            unlink(storage_path('app/public/payments/' . $payment->attachment));
        }

        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function show(Payment $payment)
    {
        return view('finance.payments.show', compact('payment'));
    }
}

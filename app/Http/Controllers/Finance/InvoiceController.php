<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Models\Invoice;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $invoices = Invoice::with('serviceRequest')->latest();

        return DataTables::of($invoices)
            ->addIndexColumn()
            ->addColumn('request', function($row) {
                return $row->serviceRequest->title ?? 'Request #'.$row->service_request_id ?? 'N/A';
            })
            ->addColumn('amount', function($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('status', function($row) {
                $class = $row->status == 'Paid' ? 'bg-success' : 'bg-warning text-dark';
                return '<span class="badge '.$class.'">'.$row->status.'</span>';
            })
            ->addColumn('notes', function($row) {
                return \Illuminate\Support\Str::limit($row->notes, 50, '...');
            })
            ->addColumn('attachment', function($row) {
                if($row->attachment){
                    return '<a href="'.asset('storage/'.$row->attachment).'" target="_blank" class="btn btn-info btn-sm">View</a>';
                }
                return '<span class="badge bg-secondary">No File</span>';
            })
            ->addColumn('action', function($row){
                return '
                    <a href="'.route('finance.invoices.edit',$row->id).'" class="btn btn-primary btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>
                ';
            })
            ->rawColumns(['status','attachment','action'])
            ->make(true);
    }

    return view('finance.invoices.index');
}

    public function create()
{
    // sirf user ka department ka procurements
    $user = auth()->user();

    if($user->role == 'admin'){
        $procurements = \App\Models\Procurement::where('status','approved')->get();
    } else {
        $procurements = \App\Models\Procurement::where('department_id', $user->department_id)->where('status','approved')->get();
    }

    // invoice number generate karna
    $lastInvoice = \App\Models\Invoice::latest()->first();
    $invoice_no = $lastInvoice ? 'INV-' . ($lastInvoice->id + 1) : 'INV-1001';

    return view('finance.invoices.create', compact('procurements','invoice_no'));
}

    public function store(Request $r)
    {
        $r->validate([
            'request_id'   => 'required|exists:service_requests,id',
            'amount'       => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'notes'        => 'nullable|string|max:65535',
            'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $lastInvoice = Invoice::latest('id')->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $invoiceNo = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $status = (Auth::check() && Auth::user()->role === 'admin')
            ? ($r->status ?? 'Unpaid')
            : 'Unpaid';

        $data = [
            'request_id'   => $r->request_id,
            'invoice_no'   => $invoiceNo,
            'amount'       => $r->amount,
            'invoice_date' => Carbon::parse($r->invoice_date)->format('Y-m-d'),
            'status'       => $status,
            'notes'        => $r->notes ?? null,
        ];

        if ($r->hasFile('attachment')) {
            $data['attachment'] = $r->file('attachment')->store('invoices', 'public');
        }

        Invoice::create($data);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
    }

    public function edit(Invoice $invoice)
    {
        $requests = ServiceRequest::all();
        return view('finance.invoices.edit', compact('invoice', 'requests'));
    }

    public function update(Request $r, Invoice $invoice)
    {
        $r->validate([
            'request_id'   => 'required|exists:service_requests,id',
            'amount'       => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'notes'        => 'nullable|string|max:65535',
            'attachment'   => 'nullable',
        ]);

        $status = (Auth::check() && Auth::user()->role === 'admin')
            ? ($r->status ?? $invoice->status)
            : $invoice->status;

        $data = [
            'request_id'   => $r->request_id,
            'amount'       => $r->amount,
            'invoice_date' => Carbon::parse($r->invoice_date)->format('Y-m-d'),
            'status'       => $status,
            'notes'        => $r->notes ?? null,
        ];

        if ($r->hasFile('attachment')) {
            if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
                Storage::disk('public')->delete($invoice->attachment);
            }
            $data['attachment'] = $r->file('attachment')->store('invoices', 'public');
        }

        $invoice->update($data);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
            Storage::disk('public')->delete($invoice->attachment);
        }
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        $pdf = Pdf::loadView('finance.invoices.pdf', compact('invoice'));
        return $pdf->download('invoice_' . $invoice->id . '.pdf');
    }
}
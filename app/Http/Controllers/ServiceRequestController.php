<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $serviceRequests = ServiceRequest::with('invoice')->get();
        return view('services.index', compact('serviceRequests'));
    }

    public function create()
    {
        $invoices = Invoice::all(); // Dropdown ke liye
        return view('services.create', compact('invoices'));
    }

  public function store(Request $request)
{
    // ✅ Validation
    $request->validate([
        'request_no' => 'required|unique:service_requests,request_no',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|string|in:pending,approved,rejected',
    ]);

    // ✅ Create new service request safely
    $serviceRequest = ServiceRequest::create([
        'request_no'  => $request->request_no,
        'title'       => $request->title,
        'description' => $request->description,
        'status'      => $request->status,
    ]);

    // ✅ Redirect with success message
    return redirect()->route('services.index')
                     ->with('success', 'Service Request created successfully.');
}



    public function edit(ServiceRequest $serviceRequest)
    {
        $invoices = Invoice::all();
        return view('services.edit', compact('serviceRequest', 'invoices'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'description' => 'required|string',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $serviceRequest->update($request->all());

        return redirect()->route('services.index')->with('success', 'Service Request updated successfully.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        return view('services.show', compact('serviceRequest'));
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $serviceRequest->delete();
        return redirect()->route('services.index')->with('success', 'Service Request deleted successfully.');
    }
}

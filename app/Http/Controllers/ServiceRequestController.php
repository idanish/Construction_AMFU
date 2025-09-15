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
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // Auto generate Request No
    $lastId = \App\Models\ServiceRequest::max('id') + 1;
    $requestNo = 'SR-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);

    ServiceRequest::create([
        'request_no' => $requestNo,
        'title' => $request->title,
        'description' => $request->description,
        'status' => 'pending',
    ]);

    return redirect()->route('services.index')
        ->with('success', 'Work Order created successfully!');
}




    public function edit(ServiceRequest $serviceRequest)
    {
        $invoices = Invoice::all();
        return view('services.edit', compact('serviceRequest', 'invoices'));
    }

public function update(Request $request, ServiceRequest $serviceRequest)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
    ]);

    // title aur description update karna
    $serviceRequest->update([
        'title' => $request->title,
        'description' => $request->description,
    ]);

    return redirect()->route('services.index')
        ->with('success', 'Work Order updated successfully.');
}

public function show(ServiceRequest $serviceRequest)
{
    return view('services.show', compact('serviceRequest'));
}

    public function destroy(ServiceRequest $serviceRequest)
    {
        $serviceRequest->delete();
        return redirect()->route('services.index')->with('success', 'Work Order deleted successfully.');
    }
}

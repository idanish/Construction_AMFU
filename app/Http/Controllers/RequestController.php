<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Department;
use App\Models\User;
use App\Models\ApprovalLevel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class RequestController extends Controller
{

    // public function index(Request $r)
    // {
    //     // 1. Pagination Setup
    //     $perPage = $r->input('per_page', 10); // Default 10 requests per page
    //     if (!in_array($perPage, [5, 10, 25, 50, 100])) {
    //         $perPage = 10;
    //     }

        
    //     $requestsQuery = RequestModel::with(['requestor', 'department'])->latest();

    //     // 2. Filters Implementation

    //     // Filter By Requestor (requestor_id)
    //     if ($r->filled('requestor_id')) {
    //         $requestsQuery->where('requestor_id', $r->requestor_id);
    //     }

    //     // Filter By Status (status)
    //     if ($r->filled('status')) {
    //         $requestsQuery->where('status', $r->status);
    //     }

    //     // Date Range Filter (start_date and end_date)
    //     if ($r->filled('start_date') && $r->filled('end_date')) {
    //         $requestsQuery->whereBetween('created_at', [
    //             $r->start_date . " 00:00:00", // Start of the start day
    //             $r->end_date . " 23:59:59"    // End of the end day
    //         ]);
    //     }

    //     // Final Data Fetching
    //     $requests = $requestsQuery->paginate($perPage)->withQueryString();
        
        
    //     $departments = Department::all();
    //     $allRequestors = User::orderBy('name')->get(); // Filters

    //     return view('requests.index', compact('requests', 'departments', 'allRequestors'));
    // }



public function index(Request $r)
{
    $perPage = $r->input('per_page', 10);
    if (!in_array($perPage, [5, 10, 25, 50, 100])) {
        $perPage = 10;
    }

    $user = auth()->user();

    $requestsQuery = RequestModel::with(['requestor', 'department'])->latest();

    // Department-based access
    if (!$user->hasRole('super-admin')) {
        $requestsQuery->where('department_id', $user->department_id);
    }

    // Filters
    if ($r->filled('requestor_id')) {
        $requestsQuery->where('requestor_id', $r->requestor_id);
    }
    if ($r->filled('status')) {
        $requestsQuery->where('status', $r->status);
    }
    if ($r->filled('start_date') && $r->filled('end_date')) {
        $requestsQuery->whereBetween('created_at', [
            $r->start_date . " 00:00:00",
            $r->end_date . " 23:59:59"
        ]);
    }

    $requests = $requestsQuery->paginate($perPage)->withQueryString();
    $departments = Department::all();
    $allRequestors = User::orderBy('name')->get();

    return view('requests.index', compact('requests', 'departments', 'allRequestors'));
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
{

    $departments = Department::all();


    return view('requests.create', compact('departments'));
}
    /**
     * Store a newly created resource in storage.
     */
//     public function store(Request $request)
// {
//     $request->validate([
//         'requestor_id' => 'required|exists:users,id',
//         'department_id' => 'required|exists:departments,id',
//         'title' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'amount' => 'required|numeric|min:0',
//     ]);

//     RequestModel::create(array_merge($request->all(), [
//         'status' => 'Pending'
//     ]));

//     if($request->hasFile('attachments')) {
//         foreach ($request->file('attachments') as $file) {
//             $requestModel->addMedia($file)->toMediaCollection('attachments');
//         }
//     }


//     return redirect()->route('requests.index')->with('success', 'Request created successfully.');
// }


public function store(Request $request)
{
    $request->validate([
        'requestor_id' => 'required|exists:users,id',
        'department_id' => 'required|exists:departments,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'amount' => 'required|numeric|min:0',
    ]);

    // Create request (Mass assignment avoided)
    $requestModel = RequestModel::create([
        'requestor_id'  => $request->requestor_id,
        'department_id' => $request->department_id,
        'title'         => $request->title,
        'description'   => $request->description,
        'amount'        => $request->amount,
        'status'        => 'pending',
        'current_level' => 1
    ]);

    // Attachments handling via Spatie Media Library
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $requestModel->addMedia($file)->toMediaCollection('attachments');
        }
    }

    return redirect()->route('requests.index')->with('success', 'Request created successfully.');
}


    public function show($id)
    {
        $request = RequestModel::with(['requestor', 'department'])->findOrFail($id);
        return view('requests.show', compact('request'));
    }


public function edit($id)
{
    $requestModel = RequestModel::with('media')->findOrFail($id);
    $departments = Department::all();

    return view('requests.edit', compact('requestModel', 'departments'));
}




    /**
     * Update the specified resource in storage.
     */
//     public function update(Request $request, $id)
// {
//     $request->validate([
//         'requestor_id' => 'required|exists:users,id',
//         'department_id' => 'required|exists:departments,id',
//         'title' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'amount' => 'required|numeric|min:0',
//     ]);

//     $req = RequestModel::findOrFail($id);

//     $req->update([
//         'requestor_id' => $request->requestor_id,
//         'department_id' => $request->department_id,
//         'title' => $request->title,
//         'description' => $request->description,
//         'amount' => $request->amount,
//         // Optional: 'status' => $request->status ?? $req->status
//     ]);

//     return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
// }

public function update(Request $request, $id)
{
    $requestModel = RequestModel::findOrFail($id);

    $request->validate([
        'requestor_id' => 'required|exists:users,id',
        'department_id'=> 'required|exists:departments,id',
        'title'        => 'required|string|max:255',
        'description'  => 'nullable|string',
        'amount'       => 'required|numeric|min:0',
    ]);

    // Update main request fields
    $requestModel->update([
        'requestor_id'  => $request->requestor_id,
        'department_id' => $request->department_id,
        'title'         => $request->title,
        'description'   => $request->description,
        'amount'        => $request->amount,
    ]);

    // Handling attachments
    if ($request->hasFile('attachments')) {

        // Optional: Remove existing attachments if "replace all" logic
        if ($request->input('replace_attachments')) {
            $requestModel->clearMediaCollection('attachments');
        }

        foreach ($request->file('attachments') as $file) {
            $requestModel->addMedia($file)->toMediaCollection('attachments');
        }
    }

    return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $req = RequestModel::findOrFail($id);
        $req->delete();

        return redirect()->route('requests.index')->with('success', 'Request deleted successfully.');
    }


    // Approved Request
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        $request_item = RequestModel::find($id);

        if ($request_item) {
            $request_item->status = $request->input('status');
            $request_item->save();
            
            return redirect()->back()->with('success', 'Status updated successfully!');
        }

        return redirect()->back()->with('error', 'Request not found!');
    }

}
<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Sab requests fetch karo, relations ke saath
    $requests = RequestModel::with(['requestor', 'department'])->latest()->get();

    // View me $requests pass karo
    return view('requests.index', compact('requests'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    // Sab departments fetch karo
    $departments = Department::all();

    // View ko $departments pass karo
    return view('requests.create', compact('departments'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'requestor_id' => 'required|exists:users,id',
        'department_id' => 'required|exists:departments,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'amount' => 'required|numeric|min:0',
    ]);

    RequestModel::create(array_merge($request->all(), [
        'status' => 'Pending'
    ]));

    return redirect()->route('requests.index')->with('success', 'Request created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $request = RequestModel::with(['requestor', 'department'])->findOrFail($id);
        return view('requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) 
{
    $request = RequestModel::findOrFail($id);

    // Add this line to fetch all departments
    $departments = Department::all();

    return view('requests.edit', compact('request', 'departments'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'requestor_id' => 'required|exists:users,id',
        'department_id' => 'required|exists:departments,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'amount' => 'required|numeric|min:0',
    ]);

    $req = RequestModel::findOrFail($id);

    $req->update([
        'requestor_id' => $request->requestor_id,
        'department_id' => $request->department_id,
        'title' => $request->title,
        'description' => $request->description,
        'amount' => $request->amount,
        // Optional: 'status' => $request->status ?? $req->status
    ]);

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
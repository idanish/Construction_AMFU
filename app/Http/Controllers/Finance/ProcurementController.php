<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Department;
use App\Models\Procurement;

class ProcurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $procurements = Procurement::with('department')->latest()->get();
        return view('finance.procurements.index', compact('procurements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('finance.procurements.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name'     => 'required|string|max:255',
            'quantity'      => 'required|numeric|min:1',
            'cost_estimate' => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id',
            'justification' => 'nullable|string',
            'status'        => 'required|in:pending,approved,rejected',
            'attachment'    => 'nullable|file',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('procurements', 'public');
        }

        Procurement::create([
            'item_name'     => $request->item_name,
            'quantity'      => $request->quantity,
            'cost_estimate' => $request->cost_estimate,
            'department_id' => $request->department_id,
            'justification' => $request->justification,
            'status'        => $request->status,
            'attachment'    => $attachmentPath,
        ]);

        return redirect()->route('finance.procurements.index')
                         ->with('success', 'Procurement created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $procurement = Procurement::with('department')->findOrFail($id);
        return view('finance.procurements.show', compact('procurement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $procurement = Procurement::findOrFail($id);
        $departments = Department::all();
        return view('finance.procurements.edit', compact('procurement', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name'     => 'required|string|max:255',
            'quantity'      => 'required|numeric|min:1',
            'cost_estimate' => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id',
            'justification' => 'nullable|string',
            'status'        => 'nullable|in:pending,approved,rejected',
            'attachment'    => 'nullable|file',
        ]);

        $procurement = Procurement::findOrFail($id);

        $data = $request->only(['item_name', 'quantity', 'cost_estimate', 'department_id', 'justification', 'status']);

        // Normal users cannot update status
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'officer') {
            $data['status'] = $procurement->status;
        }

        // Attachment handle
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('procurements', 'public');
        }

        $procurement->update($data);

        return redirect()->route('finance.procurements.index')
                         ->with('success', 'Procurement updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $procurement = Procurement::findOrFail($id);
        $procurement->delete();

        return redirect()->route('finance.procurements.index')
                         ->with('success', 'Procurement deleted successfully!');
    }
}

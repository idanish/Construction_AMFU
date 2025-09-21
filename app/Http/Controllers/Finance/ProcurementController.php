<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Models\ProcurementApproval;
use App\Models\Department;
use App\Events\ProcurementApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    public function index()
{
    $procurements = \App\Models\Procurement::with('department')->latest()->get();
    return view('finance.procurements.index', compact('procurements'));
}
   public function create()
    {
        $departments = Department::all();
        return view('finance.procurements.create', compact('departments'));
    }

   public function store(Request $request)
    {
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'cost_estimate' => 'required|numeric|min:0',
            'department_id' => 'nullable|exists:departments,id',
            'justification' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,xlsx|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('procurements', 'public');
        }

        Procurement::create($data);

        return redirect()->route('finance.procurements.index')
                         ->with('success', 'Procurement created successfully!');
    }

    // Reject procurement
    // public function reject(Request $request, Procurement $procurement)
    // {
    //     $user = auth()->user();

    //     DB::transaction(function() use ($procurement, $user, $request) {
    //         ProcurementApproval::create([
    //             'procurement_id' => $procurement->id,
    //             'approved_by' => $user->id,
    //             'role' => $user->roles->pluck('name')->first(),
    //             'status' => 'rejected',
    //             'remarks' => $request->remarks ?? null,
    //         ]);

    //         $procurement->update(['status' => 'rejected']);
    //     });

    //     return back()->with('error', 'Procurement Rejected.');
    // }

    // public function show($id)
    // {
    //     $procurement = Procurement::with('department')->findOrFail($id);
    //     return view('finance.procurements.show', compact('procurement'));
    // }

    public function edit($id)
    {
        $procurement = Procurement::findOrFail($id);
        $departments = Department::all();
        return view('finance.procurements.edit', compact('procurement', 'departments'));
    }

    public function update(Request $r, $id)
    {
        $proc = Procurement::findOrFail($id);

        $r->validate([
            'item_name'     => 'required|string|max:255',
            'quantity'      => 'required|numeric|min:1',
            'cost_estimate' => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id',
            'justification' => 'nullable|string',
            'attachment'    => 'nullable|file|max:5120',
        ]);

        $data = $r->only(['item_name','quantity','cost_estimate','department_id','justification']);

        if ($r->hasFile('attachment')) {
            $data['attachment'] = $r->file('attachment')->store('procurements', 'public');
        }

        $proc->update($data);

        return redirect()->route('finance.procurements.index')->with('success','Procurement updated successfully!');
    }

    public function destroy($id)
    {
        $proc = Procurement::findOrFail($id);
        $proc->delete();

        return redirect()->route('finance.procurements.index')->with('success','Procurement deleted successfully!');
    }
    
}
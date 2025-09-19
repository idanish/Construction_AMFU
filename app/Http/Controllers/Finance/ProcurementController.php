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
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Procurement::with('department')->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('department_name', function($row){
                return $row->department->name ?? 'N/A';
            })
            ->addColumn('status', function($row){
                if($row->status == 'pending'){
                    return '<span class="badge bg-warning">Pending</span>';
                }elseif($row->status == 'approved'){
                    return '<span class="badge bg-success">Approved</span>';
                }else{
                    return '<span class="badge bg-danger">Rejected</span>';
                }
            })
            ->addColumn('attachment', function($row){
                if($row->attachment){
                    return '<a href="'.asset('storage/'.$row->attachment).'" target="_blank" class="btn btn-sm btn-outline-info">View</a>';
                }
                return '<span class="text-muted">No File</span>';
            })
            ->addColumn('action', function($row){
                // $btn = '<a href="'.route('finance.procurements.show', $row->id).'" class="btn btn-info btn-sm">Show</a> ';
                $btn .= '<a href="'.route('finance.procurements.edit', $row->id).'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<button data-id="'.$row->id.'" class="btn btn-danger btn-sm delete-btn">Delete</button>';
                return $btn;
            })
            ->rawColumns(['status','attachment','action'])
            ->make(true);
    }

    return view('finance.procurements.index');
}
   public function create()
    {
        $departments = Department::all();
        return view('finance.procurements.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'cost_estimate' => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id',
            'justification' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $path = $request->file('attachment') ? $request->file('attachment')->store('procurements', 'public') : null;

            $procurement = Procurement::create([
                'item_name' => $request->item_name,
                'quantity' => $request->quantity,
                'cost_estimate' => $request->cost_estimate,
                'department_id' => $request->department_id,
                'justification' => $request->justification,
                'attachment' => $path,
                'status' => 'pending',
            ]);

            // Initial approval for HOD
            ProcurementApproval::create([
                'procurement_id' => $procurement->id,
                'role' => 'HOD',
                'status' => 'pending',
            ]);

            DB::commit();
            return redirect()->route('finance.procurements.index')->with('success', 'Procurement created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    // Approve procurement
    public function approve(Request $request, Procurement $procurement)
    {
        $user = auth()->user();

        DB::transaction(function() use ($procurement, $user, $request) {
            ProcurementApproval::create([
                'procurement_id' => $procurement->id,
                'approved_by' => $user->id,
                'role' => $user->roles->pluck('name')->first(),
                'status' => 'approved',
                'remarks' => $request->remarks ?? null,
            ]);

            if($user->hasRole('Finance')) {
                $procurement->update(['status' => 'approved']);
                event(new ProcurementApproved($procurement));
            }
        });

        return back()->with('success', 'Procurement Approved.');
    }

    // Reject procurement
    public function reject(Request $request, Procurement $procurement)
    {
        $user = auth()->user();

        DB::transaction(function() use ($procurement, $user, $request) {
            ProcurementApproval::create([
                'procurement_id' => $procurement->id,
                'approved_by' => $user->id,
                'role' => $user->roles->pluck('name')->first(),
                'status' => 'rejected',
                'remarks' => $request->remarks ?? null,
            ]);

            $procurement->update(['status' => 'rejected']);
        });

        return back()->with('error', 'Procurement Rejected.');
    }

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